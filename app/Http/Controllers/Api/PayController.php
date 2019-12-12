<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Resources\TransactionResource;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dare\Services\PaymentService;

/**
 * @group Payments
 */
class PayController extends Controller
{
    public $paySystem;
    protected $gateway;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api')->only(['checkout']);

        $gateway = $request->route()->parameter('gateway');
        $this->$gateway = $gateway;
        $this->paySystem = PaymentService::init($gateway);
        $this->paySystem->init();
    }

    /**
     * Create a payment.
     * Add money to authorized user account. Donation to user or task.
     *
     * {gate} - gateway required. Only Payapal or Stripe. Example: PayPal
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

        $user = auth()->user();

        if ((intval($user_id) > 0 && intval($task_id) > 0) || (intval($user_id) == 0 && intval($task_id) == 0))
            return setErrorAfterValidation(['user' => trans('api/paypal.user_task_failed')]);

        if (intval($user_id) > 0)
            $user = User::findOrFail($user_id);

        if (intval($task_id) > 0) {
            $task = Task::findOrFail($task_id);

            if ($task->status != TaskStatus::Active && !(auth()->user()->id == $task->user_id && $task->status == TaskStatus::Created))
                return setErrorAfterValidation(['status' => trans('api/transaction.failed_task_not_active')]);
        }

        $currency = "RUB";

        $data = [
            'account_receiver_id' => $user->account->id,
            'amount' => $request->get('amount'),
            'comment' => "Money transfer from PayPal.",
            'currency' => $currency,
            'payment' => $this->gateway,
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
        if (intval($task_id) > 0)
            $description .= "task:" . $task_id;
        else if (intval($user_id) > 0)
            $description .= "user:" . $user_id;
        else
            $description = "Transfer to myself.";

        return $this->paySystem->checkout([
            'amount' => $result->amount,
            'currency' => $currency,
            'transactionId' => $result->id,
            'description' => $description,
            'result' => $result,
            'user_id' => $user_id,
            'task_id' => $task_id
        ]);
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
        $result = $this->paySystem->completed($request);
        $error = 0;

        if(empty($result))
        {
            $response = 0;
            $error = trans('api/paypal.purchase_mistake');
        }else{
            if($result['status']=="completed") {
                $data = $result['data'];
                $amount = $result['amount'];
                $currency = $result['currency'];
                $description = $result['description'];

                $t = Transaction::findOrFail($result['order_id']);

                if ($t)
                {
                    if (strtolower($t->currency) != strtolower($currency))
                        return setErrorAfterValidation(['currency' => trans('api/paypal.currency_not_match')]);

                    if ($t->status != TransactionStatus::Completed) {

                        try {
                            $t = DB::transaction(function () use ($data, $t, $amount, $user_id, $task_id, $description) {

                                $t->update([
                                    'status' => TransactionStatus::Completed,
                                    'amount' => $amount,
                                    'money' => $amount,
                                    'json' => $data
                                ]);

                                //Create donation for user or task
                                if (intval($task_id) > 0 && strpos($description, "task:" . $task_id) !== false)
                                    $task = Task::findOrFail($task_id);
                                else
                                    $task = 0;

                                if (intval($user_id) > 0 && strpos($description, "user:" . $user_id) !== false)
                                    $user = User::findOrFail($user_id);
                                else
                                    $user = 0;

                                if (($user || $task) && ($t->accountReceiver->amount >= $t->amount))    // amount sender > amount to donate
                                {
                                    $receiver_id = $task ? $task->stream->user->account->id : $user->account->id;
                                    $sender_id = $t->account_receiver_id;

                                    if ($receiver_id != $sender_id) {
                                        Transaction::create([
                                            'task_id' => $task ? $task->id : 0,
                                            'amount' => intval($t->amount),
                                            'account_sender_id' => $sender_id,
                                            'account_receiver_id' => $receiver_id,
                                            'status' => $task ? TransactionStatus::Holding : TransactionStatus::Completed,
                                            'type' => TransactionType::Donation
                                        ]);
                                    }
                                }

                                return $t;
                            });

                            $response = intval($t->amount);

                        } catch (\Exception $e) {
                            $response = 0;
                            $error = $e->getMessage();
                        }
                    } else {
                        $response = 0;
                    }
                }

                //return new TransactionResource($t);
            } else {
                $response = 0;
                $error = trans('api/paypal.not_approved');
            }

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