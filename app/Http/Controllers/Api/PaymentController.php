<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Resources\TransactionResource;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\DB;

/**
 * @group Payments
 */
class PaymentController extends Controller
{
    private $gateway;
    private $gate;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api')->only(['checkout']);

        $this->gate = $request->route()->parameter('gateway');
        $gateway = Omnipay::create($this->gate);

        //Todo: Change initialize params
        //Initialise the gateway
        $gateway->initialize(array(
            'clientId' => config('services.paypal.client_id'),
            'secret'   => config('services.paypal.secret'),
            'testMode' => config('services.paypal.sandbox')
        ));

        $this->gateway = $gateway;
    }

    /**
     * Create a payment.
     * Add money to authorized user account. Donation to user or task.
     *
     * {gate} - gateway required from Omipay. Only 'PayPal_Rest' right now. Example: PayPal_Rest
     * {user} - user integer id. Default: 0
     * {task} - task integer id. Default: 0
     * {user} and {task} both cannot be >0 or =0 at the same time.
     *
     * @authenticated
     *
     * @bodyParam amount integer required Amount for payment.
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function checkout($gate, $user_id, $task_id, Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1'
        ]);

        if((intval($user_id)>0 && intval($task_id)>0) || (intval($user_id)==0 && intval($task_id)==0) )
            return setErrorAfterValidation(['user' => trans('api/paypal.user_task_failed')]);

        if(intval($user_id)>0)
            $user = User::findOrFail($user_id);

        if(intval($task_id)>0)
            $task = Task::findOrFail($task_id);

        $currency = "RUB";

        $data = [
            'account_receiver_id' => auth()->user()->account->id,
            'amount' => $request->get('amount'),
            'comment' => "Money transfer from PayPal.",
            'currency' => $currency,
            'payment' => $this->gate,
            'status' => TransactionStatus::Created,
            'type' => TransactionType::Deposit
        ];

        try {
            $result = DB::transaction(function () use ($data) {
                return Transaction::create($data);
            });
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }

        $description = 'Donation to ';
        if(intval($task_id)>0)
            $description.="task:".$task_id;
        else if(intval($user_id)>0)
            $description.="user:".$user_id;
        else
            $description = "Transfer to myself.";

        $transaction = $this->gateway->authorize(array(
            'amount'        => $result->amount,
            'currency'      => $currency,
            'transactionId' => $result->id,
            'description'   => $description,
            'returnUrl'=> route('payment.checkout.completed', ['gateway' => $this->gate, 'user' => $user_id, 'task' => $task_id]),
            'cancelUrl' => route('payment.checkout.cancelled', ['gateway' => $this->gate, 'user' => $user_id, 'task' => $task_id]),
        ));

        $response = $transaction->send();

        if ($response->isRedirect())
        {
            $data = $response->getData();
            $result->update(['exid' => $data['id']]);

            $redirectUrl = $response->getRedirectUrl();
            return redirect()->to($redirectUrl);
        }
    }

    /**
     * Complete purchase.
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function completed($gate, $user_id, $task_id, Request $request)
    {
        $transaction = $this->gateway->completePurchase(array(
            'payerId'             => $request->get('PayerID'),
            'transactionReference' => $request->get('paymentId'),
        ));

        $result = $transaction->send();
        $error = false;
        $response = 0;

        if ($data = $result->getData())
        {
            if (isset($data['state']) && $data['state'] == 'approved')
            {
                $transaction = $data['transactions'][0];
                $t = Transaction::findOrFail($transaction['invoice_number']);

                $amount = $transaction['amount']['total'];
                $currency = $transaction['amount']['currency'];
                $description = $transaction['description'];

                if ($t->currency != $currency)
                    return setErrorAfterValidation(['currency' => trans('api/paypal.currency_not_match')]);

                if ($t->status != TransactionStatus::Completed)
                {
                    try {
                        $t = DB::transaction(function () use ($data, $t, $amount, $user_id, $task_id, $description) {

                            $t->update([
                                'status' => TransactionStatus::Completed,
                                'amount' => $amount,
                                'json' => $data
                            ]);

                            //Create donation for user or task
                            if(intval($task_id)>0 && strpos($description, "task:".$task_id)!== false)
                                $task = Task::findOrFail($task_id);
                            else
                                $task = 0;

                            if(intval($user_id)>0 && strpos($description, "user:".$user_id)!== false)
                                $user = User::findOrFail($user_id);
                            else
                                $user = 0;

                            if(($user || $task) && ($t->accountReceiver->amount>=$t->amount))
                            {
                                Transaction::create([
                                    'task_id' => $task ? $task->id : 0,
                                    'amount' => intval($t->amount),
                                    'account_sender_id' => $t->account_receiver_id,
                                    'account_receiver_id' => $task ? $task->stream->user->account->id : $user->account->id,
                                    'status' => $task ? TransactionStatus::Holding : TransactionStatus::Completed,
                                    'type' => TransactionType::Donation
                                ]);
                            }

                            return $t;
                        });

                        $response = intval($t->amount);

                    }catch (\Exception $e) {
                        $response = 0;
                        $error = $e->getMessage();
                        //return response($e->getMessage(), 422);
                    }
                }else{
                    $response = 0;
                }

                //return new TransactionResource($t);
            }else{
                $response = 0;
                $error = trans('api/paypal.not_approved');
                //return response()->json(['error' => trans('api/paypal.not_approved')], 422);
            }
        }else{
            $response = 0;
            $error = trans('api/paypal.purchase_mistake');
            //return response()->json(['error' => trans('api/paypal.purchase_mistake')], 422);
        }

        return response()->view('payments.callback', [
            'result' => $response,
            'error' => $error
        ], !$error ? 422 : 200);
    }

    /**
     * Payment cancel
     *
     * @responseFile responses/response.json
     * @responseFile 404 responses/not_found.json
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelled($gate, $user, $task, Request $request)
    {
        return response()->view('payments.callback', [
            'result' => 0,
            'error' => trans('api/paypal.purchase_canceled')
        ], 200);
    }
}
