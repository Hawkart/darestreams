<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Requests\TaskTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @group Withdraw
 */
class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store']);
    }

    /**
     * Create withdraw.
     *
     * Sends email for verification the withdraw
     *
     * @param TaskTransactionRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(TaskTransactionRequest $request)
    {
        $user = auth()->user();
        $amount = $request->get('amount');

        //enough money
        if($amount <= $user->account->amount)
        {
            $code = Str::random(10);

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

                //Send verify

            } catch (\Exception $e) {
                return response($e->getMessage(), 422);
            }

            return response()->json(['success' => true], 200);

        }else{
            abort(
                response()->json(['message' => trans('api/transaction.not_enough_money')], 402)
            );
        }
    }

    /**
     * @param $code
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function verify($code)
    {
        $transaction = Transaction::where('verify_code', $code)
                        ->where('status', TransactionStatus::Created)->first();

        $account = $transaction->accountSender;

        if($transaction->amount > $account->amount)
            abort(
                response()->json(['message' => trans('api/transaction.not_enough_money')], 402)
            );

        try {
            DB::transaction(function () use ($transaction) {
                $transaction->update([
                    'status' => TransactionStatus::Holding,
                    'verify_code' => null
                ]);
            });

            //Send notification to admin

        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }

        return response()->json(['success' => true], 200);
    }
}