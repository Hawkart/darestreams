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
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * {stream} - stream integer id.
     *
     * @queryParam include string String of connections: user, stream, transactions. Example: user,stream
     * @queryParam sort string Sort items by fields: amount_donations, id. For desc use '-' prefix. Example: -amount_donations
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Stream $stream)
    {
        $query = $stream->tasks()->getQuery();

        $items = QueryBuilder::for($query)
            ->defaultSort('amount_donations')
            ->allowedSorts(['amount_donations', 'id'])
            ->allowedIncludes(['user', 'stream', 'transactions'])
            ->jsonPaginate();

        return TaskResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * {stream} - stream integer id.
     * {$task} - $task integer id.
     *
     * @queryParam include string String of connections: user, stream, transactions. Example: user,stream
     */
    public function show(Stream $stream, $task)
    {
        if(!$stream->tasks()->where('id', $task)->exists())
            return response()->json(['error' => trans('api/streams/task.failed_not_belong_to_stream')], 403);

        $item = QueryBuilder::for(Task::class)
            ->allowedIncludes(['user', 'stream', 'transactions'])
            ->findOrFail($task);

        return new TaskResource($item);
    }

    /**
     * Create new task for stream.
     * {stream} - stream integer id.
     * @authenticated
     *
     * @bodyParam small_text text required Short description.
     * @bodyParam full_text text required Full description.
     * @bodyParam interval_time integer required Time for finishing the task. 0 means until the end of the stream.
     * @bodyParam min_amount integer required Min amount for donation.
     * @bodyParam is_superbowl boolean Select superbowl or not.
     * @bodyParam min_amount_superbowl integer If is_superbowl is true required min amount for donation.
     * @bodyParam tags Additional tags to task.
     *
     */
    public function store(TaskRequest $request, Stream $stream)
    {
        $user = auth()->user();

        if(!$user->channel)
            return response()->json(['error' => trans('api/streams.failed_no_channel')], 422);

        if($user->channel->id != $stream->channel_id)
            return response()->json(['error' => trans('api/streams/task.failed_not_belong_to_channel')], 422);

        //Todo: Add transaction and validation of account and min amount donation.
        //Todo: Add tags code.

        $input = $request->all();
        $input['user_id'] = $user->id;

        $obj = new Task();
        $obj->fill($input);
        $obj->save();

        return response()->json([
            'success' => true,
            'message'=> trans('api/streams/task.success_created')
        ], 200);
    }

    /**
     * Update task for stream.
     *
     * {stream} - stream integer id.
     * {task} - task integer id.
     *
     * @authenticated
     *
     * @bodyParam small_text text Short description.
     * @bodyParam full_text text Full description.
     * @bodyParam interval_time integer Time for finishing the task. 0 means until the end of the stream.
     * @bodyParam min_amount integer Min amount for donation.
     * @bodyParam is_superbowl boolean Select superbowl or not.
     * @bodyParam min_amount_superbowl integer If is_superbowl is true required min amount for donation.
     * @bodyParam tags Additional tags to task.
     */
    public function update(TaskRequest $request, Stream $stream, Task $task)
    {
        $user = auth()->user();

        if(!$user->channel)
            return response()->json(['error' => trans('api/streams.failed_no_channel')], 422);

        if($user->channel->id != $stream->channel_id)
            return response()->json(['error' => trans('api/streams/task.failed_not_belong_to_channel')], 422);

        if(!$stream->tasks()->where('id', $task->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks.failed_not_belong_to_stream')], 422);

        $task->fill($request->all());
        $task->save();

        //Todo: Add tags code.

        return response()->json([
            'success' => true,
            'message'=> trans('api/streams/task.success_updated')
        ], 200);
    }
}
