<?php

namespace App\Http\Controllers\Api;

use App\Models\Vote;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TaskResource;
use App\Models\Task;

/**
 * @group Tasks
 */
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Task::class)
            ->allowedIncludes(['user', 'stream', 'transactions'])
            ->jsonPaginate();

        return TaskResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $task
     * @return \Illuminate\Http\Response
     */
    public function show($task)
    {
        $item = QueryBuilder::for(Task::class)
            ->allowedIncludes(['user', 'stream', 'transactions'])
            ->findOrFail($task);

        return new TaskResource($item);
    }

    /**
     * @param Task $task
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setVote(Task $task, Request $request)
    {
        $user = auth()->user();

        if($task->check_vote==Task::VOTE_FINISHED)
            return response()->json(['error' => trans('api/task.vote_finished')], 422);

        $votes = $task->votes()->where('user_id', $user->id);

        if($votes->count()==0)
            return response()->json(['error' => trans('api/task.no_vote')], 422);

        $vote = $votes->first();

        if($vote->vote!=Vote::VOTE_PENDING)
            return response()->json(['error' => trans('api/task.already_vote')], 422);

        $vote->update($request->only('vote'));

        return response()->json([
            'success' => true,
            'message'=> trans('api/task.vote_accepted')
        ], 200);
    }
}
