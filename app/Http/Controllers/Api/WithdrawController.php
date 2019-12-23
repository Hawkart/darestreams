<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\TaskTransactionRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NotifyFollowersAboutStream;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

/**
 * @group Withdraw
 */
class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'verify']);
    }

    /**
     * Create withdraw.
     *
     * @authenticated
     *
     * @param TaskTransactionRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(TaskTransactionRequest $request)
    {
        $user = auth()->user();
        $amount = $request->get('amount');

        //Todo: 1.check kyc 2.get all withdraws + amount > 40000. 3. move to validate request
        if(!$user->kyc || !$user->kyc->personal_verified)
            abort(response()->json(['message' => "KYC is not verified"], 403));

        //enough money
        if($amount <= $user->account->amount && $user->account->amount>2000)    //2000 start when it can be withdraw
        {
            $code = rand(10000000, 99999999);

            $data = [
                'amount' => $request->get('amount'),
                'account_sender_id' => $user->account->id,
                'status' => TransactionStatus::Created,
                'type' => TransactionType::Withdraw,
                'verify_code' => $code
            ];

            try {
                $transaction = DB::transaction(function () use ($data) {
                    return Transaction::create($data);
                });

                $this->sendSmsCode($code);

            } catch (\Exception $e) {
                return response($e->getMessage(), 422);
            }

            return response()->json(['success' => true], 200);

        }else{
            abort(response()->json(['message' => trans('api/transaction.not_enough_money')], 402));
        }
    }

    /**
     * @param $code
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function sendSmsCode($code)
    {
        $user = auth()->user();
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');
        $client = new Client($sid, $token);

        $client->messages->create(
            $user->kyc->phone,
            [
                'from' => $from,
                'body' => $code
            ]
        );
    }

    /**
     * Verify withdraw.
     *
     * @authenticated
     *
     * @param $code
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function verify($code)
    {
        $transaction = Transaction::where('verify_code', $code)
                        ->where('account_sender_id', auth()->user()->account->id)
                        ->where('status', TransactionStatus::Created)->first();

        $account = $transaction->accountSender;

        if($transaction->amount > $account->amount)
            abort(response()->json(['message' => trans('api/transaction.not_enough_money')], 402));

        try {
            DB::transaction(function () use ($transaction) {
                $transaction->update([
                    'status' => TransactionStatus::Holding,
                    'verify_code' => null
                ]);
            });

            $this->adminNotify($account->user, $transaction);

        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }

        return response()->json(['success' => true], 200);
    }

    /**
     * @param $user
     * @param $transaction
     */
    public function AdminNotify($user, $transaction)
    {
        $admin = User::admins()->where('email', config('mail.admin_email'))->first();

        $subject = "Withdraw ".$transaction->amount."rub. of ".$user->name;

        $details = [
            'greeting' => 'Hi '.$admin->name,
            'body' => $subject,
            'actionText' => 'View transaction',
            'actionURL' => "https://darestreams.com/admin/transactions/".$transaction->id."/edit",
            'subject' => $subject
        ];

        $admin->notify(new NotifyFollowersAboutStream($details));
    }
}