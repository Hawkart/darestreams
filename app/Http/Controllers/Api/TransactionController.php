<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StreamRequest;
use Illuminate\Support\Facades\DB;

/**
 * @group Transactions
 */
class TransactionController extends Controller
{
    /**
     * TransactionController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store']);
    }

    /**
     * Create new transaction.
     *
     * @authenticated
     *
     * @bodyParam task_id integer Task's id.
     * @bodyParam user_id integer User's id.
     * @bodyParam amount float required Amount for payment.
     *
     *
     * @param StreamRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'sometimes|required|exists:tasks,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        $user = auth()->user();
        $amount = $request->get('amount');

        $task_id = (!$request->has('task_id') || empty($request->get('task_id'))) ? 0 : $request->get('task_id');
        $user_id = (!$request->has('user_id') || empty($request->get('user_id'))) ? 0 : $request->get('user_id');

        if($task_id>0)
        {
            $task = Task::findOrFail($task_id);
            $stream = $task->stream;

            //check all rules by stream
            $stream->canMakeDonate($amount);

            //if($task->status>Task::STATUS_IN_WORK || $task->check_vote>Task::VOTE_NOT_ALLOWED)
        }

        //enough money
        if($amount <= $user->account->amount)
        {
            if($user_id>0)
                $userReceiver = User::findOrFail($user_id);

            $data = [
                'task_id' => $task_id,
                'amount' => $request->get('amount'),
                'account_sender_id' => $user->account->id,
                'account_receiver_id' => $task_id>0 ? $task->stream->user->account->id : $userReceiver->account->id,
                'status' => $task_id>0 ? Transaction::PAYMENT_HOLDING : Transaction::PAYMENT_COMPLETED
            ];

            try {
                DB::transaction(function () use ($data) {
                    return Transaction::create($data);
                });
            } catch (\Exception $e) {
                return response($e->getMessage(), 422);
            }

            return response()->json([
                'success' => true,
                'message'=> trans('api/streams/tasks/transaction.success_created')
            ], 200);

        }else{

            $diff = $request->get('amount') - $user->account->amount;

            $request->merge([
                'amount' => $diff,
            ]);

            return response()->json([
                'diff' => $diff,
                'error' => trans('api/transaction.not_enough_money')
            ], 422);
        }
    }
}
