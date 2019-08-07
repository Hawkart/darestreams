<?php

namespace App\Http\Controllers\Api\Streams\Tasks;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TransactionResource;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\Stream;

/**
 * @group Streams tasks transactions
 */
class TransactionController extends Controller
{
    /**
     * @param Request $request
     * @param Stream $stream
     * @param Task $task
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, Stream $stream, Task $task)
    {
        if(!$stream->tasks()->where('id', $task->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.task_not_belong_to_stream')], 403);

        $query = $task->transactions()->getQuery();

        $items = QueryBuilder::for($query)
            ->allowedIncludes(['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'])
            ->jsonPaginate();

        return TransactionResource::collection($items);
    }

    /**
     * @param Stream $stream
     * @param Task $task
     * @param Transaction $transaction
     * @return TransactionResource
     */
    public function show(Stream $stream, Task $task, Transaction $transaction)
    {
        if(!$stream->tasks()->where('id', $task->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.task_not_belong_to_stream')], 403);

        if(!$task->transactions()->where('id', $transaction->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.transaction_not_belong_to_task')], 403);

        $item = QueryBuilder::for(Transaction::whereId($transaction->id))
            ->allowedIncludes(['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'])
            ->first();

        return new TransactionResource($item);
    }
}
