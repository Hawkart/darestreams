<?php

namespace App\Http\Controllers\Api\Streams;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\Stream;

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
        //Todo: check task belogs to stream

        $item = QueryBuilder::for(Task::whereId($task->id))
            ->allowedIncludes(['user', 'stream', 'transactions'])
            ->first();

        return new TaskResource($item);
    }

    /**
     * @param Request $request
     * @param Stream $stream
     */
    public function store(Request $request, Stream $stream)
    {

    }

    /**
     * @param Request $request
     * @param Stream $stream
     * @param Task $task
     */
    public function update(Request $request, Stream $stream, Task $task)
    {

    }
}
