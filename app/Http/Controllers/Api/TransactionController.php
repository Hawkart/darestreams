<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Resources\TaskResource;
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
     * @bodyParam amount integer required Amount for payment.
     *
     *
     * @param StreamRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required_without:user_id|required|exists:tasks,id',
            'user_id' => 'required_without:task_id|required|exists:users,id',
            'amount' => 'required|integer|min:1'
        ]);

        $user = auth()->user();
        $amount = $request->get('amount');

        $task_id = (!$request->has('task_id') || empty($request->get('task_id'))) ? 0 : $request->get('task_id');
        $user_id = (!$request->has('user_id') || empty($request->get('user_id'))) ? 0 : $request->get('user_id');

        if($task_id>0)
        {
            $task = Task::findOrFail($task_id);
            if($task->status!=TaskStatus::Active)
                return setErrorAfterValidation(['status' => trans('api/transaction.failed_task_not_active')]);

            if($amount<$task->min_donation)
                return setErrorAfterValidation(['min_donation' => trans('api/transaction.not_enough_money')]);
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
                'status' => $task_id>0 ? TransactionStatus::Holding : TransactionStatus::Completed,
                'type' => TransactionType::Donation
            ];

            try {
                $transaction = DB::transaction(function () use ($data) {
                    return Transaction::create($data);
                });
            } catch (\Exception $e) {
                return response($e->getMessage(), 422);
            }

            TaskResource::withoutWrapping();

            return response()->json([
                'data' => new TaskResource($transaction->task),
                'success' => true,
                'message'=> trans('api/streams/tasks/transaction.success_created')
            ], 200);

        }else{
            abort(402);
        }
    }

    /**
     * Get list of statuses
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statuses()
    {
        return response()->json(TransactionStatus::getInstances(), 200);
    }

    /**
     * Get list of types
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function types()
    {
        return response()->json(TransactionType::getInstances(), 200);
    }
}
