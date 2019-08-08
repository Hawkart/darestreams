<?php

namespace App\Http\Controllers\Api\Streams\Tasks;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TaskTransactionRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TransactionResource;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\Stream;

/**
 * @group Streams tasks donations
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
     * List of task's donations.
     *
     * {stream} - stream integer id.
     * {task} - task integer id.
     *
     * @queryParam include string String of connections: account_sender, account_receiver, account_sender.user, account_receiver.user, task. Example: task
     * @queryParam sort string Sort items by fields: created_at, created_at. For desc use '-' prefix. Example: -created_at
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     *
     * @param Request $request
     * @param Stream $stream
     * @param Task $task
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, Stream $stream, Task $task)
    {
        if(!$stream->tasks()->where('id', $task->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.task_not_belong_to_stream')], 422);

        $query = $task->transactions()->getQuery();

        $items = QueryBuilder::for($query)
            ->defaultSort('-created_at')
            ->allowedSorts(['created_at', 'amount'])
            ->allowedIncludes(['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'])
            ->jsonPaginate();

        return TransactionResource::collection($items);
    }

    /**
     * Detail donations of task.
     *
     * {stream} - stream integer id.
     * {task} - task integer id.
     * {transaction} - transaction integer id.
     *
     * @queryParam include string String of connections: account_sender, account_receiver, account_sender.user, account_receiver.user, task. Example: task
     * @param Stream $stream
     * @param Task $task
     * @param Transaction $transaction
     * @return TransactionResource
     */
    public function show(Stream $stream, Task $task, $transaction)
    {
        if(!$stream->tasks()->where('id', $task->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.task_not_belong_to_stream')], 422);

        if(!$task->transactions()->where('id', $transaction)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.transaction_not_belong_to_task')], 422);

        $item = QueryBuilder::for(Transaction::class)
            ->allowedIncludes(['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'])
            ->findOrFail($transaction);

        return new TransactionResource($item);
    }

    /**
     * Create new task for stream.
     * {stream} - stream integer id.
     * @authenticated
     *
     * @bodyParam amount integer required Amount for donation.
     *
     */
    public function store(TaskTransactionRequest $request, Stream $stream, Task $task)
    {
        $user = auth()->user();

        if(!$stream->tasks()->where('id', $task->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.task_not_belong_to_stream')], 422);

        if($request->get('amount') > $user->account->amount)
            return response()->json(['error' => trans('api/streams/tasks/transaction.not_enough_money')], 422);

        Transaction::create([
            'task_id' => $task->id,
            'amount' => $request->get('amount'),
            'account_sender_id' => $user->account->id,
            'account_receiver_id' => $stream->user->account->id
        ]);

        return response()->json([
            'success' => true,
            'message'=> trans('api/streams/tasks/transaction.success_created')
        ], 200);
    }
}
