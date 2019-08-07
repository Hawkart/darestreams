<?php

namespace App\Http\Controllers\Api\Streams;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\Stream;

/**
 * @group Streams tasks
 */
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Stream $stream)
    {
        $query = $stream->tasks()->getQuery();

        $items = QueryBuilder::for($query)
            ->allowedIncludes(['user', 'stream', 'transactions'])
            ->jsonPaginate();

        return TaskResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Stream $stream, Task $task)
    {
        if(!$stream->tasks()->where('id', $task->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.task_not_belong_to_stream')], 403);

        $item = QueryBuilder::for(Task::whereId($task->id))
            ->allowedIncludes(['user', 'stream', 'transactions'])
            ->first();

        return new TaskResource($item);
    }

    /**
     * @param TaskRequest $request
     * @param Stream $stream
     */
    public function store(TaskRequest $request, Stream $stream)
    {

    }

    /**
     * @param TaskRequest $request
     * @param Stream $stream
     * @param Task $task
     */
    public function update(TaskRequest $request, Stream $stream, Task $task)
    {
        if(!$stream->tasks()->where('id', $task->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.task_not_belong_to_stream')], 403);
    }
}
