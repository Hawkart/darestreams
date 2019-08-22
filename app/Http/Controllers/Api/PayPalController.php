<?php

namespace App\Http\Controllers\Api;

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
class PayPalController extends Controller
{
    private $gateway;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['checkout']);

        $gateway = Omnipay::create('PayPal_Rest');

        // Initialise the gateway
        $gateway->initialize(array(
            'clientId' => config('services.paypal.client_id'),
            'secret'   => config('services.paypal.secret'),
            'testMode' => config('services.paypal.sandbox')
        ));

        $this->gateway = $gateway;
    }

    /**
     * @authenticated
     *
     * Create a payment.
     * Add money to authorized user account. Donation to user or task.
     *
     * {user} - user integer id. Default: 0
     * {task} - task integer id. Default: 0
     *
     * @bodyParam amount float required Amount for payment.
     *
     * {user} and {task} cannot be >0 at the same time.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function checkout($user_id, $task_id, Request $request)
    {
        $request->validate([
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        if((intval($user_id)>0 && intval($task_id)>0) || (intval($user_id)==0 && intval($task_id)==0) )
            return response()->json(['error' => trans('api/paypal.user_task_failed')], 422);

        if(intval($user_id)>0)
            $user = User::findOrFail($user_id);

        if(intval($task_id)>0)
            $task = Task::findOrFail($task_id);

        $currency = "RUB";

        $data = [
            'account_receiver_id' => auth()->user()->account->id,
            'amount' => $request->get('amount'),
            'comment' => "Money transfer from PayPal.",
            'currency' => $currency
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
            'returnUrl'=> route('paypal.checkout.completed', ['user' => $user_id, 'task' => $task_id]),
            'cancelUrl' => route('paypal.checkout.cancelled', ['user' => $user_id, 'task' => $task_id]),
        ));

        $response = $transaction->send();

        if ($response->isRedirect())
        {
            $redirectUrl = $response->getRedirectUrl();
            return redirect()->to($redirectUrl);
        }
    }

    /**
     * Complete purchase.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function completed($user_id, $task_id, Request $request)
    {
        $transaction = $this->gateway->completePurchase(array(
            'payerId'             => $request->get('PayerID'),
            'transactionReference' => $request->get('paymentId'),
        ));

        $result = $transaction->send();

        if ($data = $result->getData())
        {
            $transaction = $data['transactions'][0];

            if ($data['state'] == 'approved')
            {
                $t = Transaction::findOrFail($transaction['invoice_number']);

                $amount = $transaction['amount']['total'];
                $currency = $transaction['amount']['currency'];
                $description = $transaction['description'];

                if ($t->currency != $currency)
                    return response()->json(['error' => trans('api/paypal.currency_not_match')], 422);

                if ($t->status != Transaction::PAYMENT_COMPLETED)
                {
                    try {
                        $t = DB::transaction(function () use ($data, $t, $amount, $user_id, $task_id, $description) {

                            $t->update([
                                'status' => Transaction::PAYMENT_COMPLETED,
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

                            if($user || $task)
                            {
                                Transaction::create([
                                    'task_id' => $task ? $task->id : 0,
                                    'amount' => $t->amount,
                                    'account_sender_id' => $t->account_receiver_id,
                                    'account_receiver_id' => $task ? $task->stream->user->account->id : $user->account->id,
                                    'status' => $task ? Transaction::PAYMENT_HOLDING : Transaction::PAYMENT_COMPLETED
                                ]);
                            }

                            return $t;
                        });
                    }catch (\Exception $e) {
                        return response($e->getMessage(), 422);
                    }
                }

                return new TransactionResource($t);
            }else{
                return response()->json(['error' => trans('api/paypal.not_approved')], 422);
            }
        }else{
            return response()->json(['error' => trans('api/paypal.purchase_mistake')], 422);
        }
    }

    /**
     * Payment cancel
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelled($user, $task, Request $request)
    {
        return response()->json(['error' => trans('api/paypal.purchase_canceled')], 422);
    }
}
