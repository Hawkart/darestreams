<?php

namespace App\Http\Controllers\Api\Streams;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Psy\Util\Str;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\Stream;
use Illuminate\Support\Facades\DB;

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
     * @bodyParam is_superbowl boolean Select superbowl or not.
     * @bodyParam tags Additional tags to task.
     *
     */
    public function store(TaskRequest $request, Stream $stream)
    {
        $user = auth()->user();

        $stream->canTaskCreate();
        $amount = $stream->getTaskCreateAmount();
        $minDonate = $stream->getDonateCreateAmount();

        $input = $request->all();
        $input['user_id'] = $user->id;
        $input['min_donation'] = $minDonate;

        if($input['created_amount']<$amount)
        {
            return response()->json(['errors' =>[
                'created_amount' => trans('api/streams/task.not_enough_money')
            ]], 422);
        }

        //If not owner of stream check how much money you have
        if($user->channel->id != $stream->channel_id && $user->account->amount<$amount)
            return response()->json(['error' => trans('api/streams/task.not_enough_money')], 422);

        try {
            $task = DB::transaction(function () use ($input, $user, $stream, $amount) {

                $task = new Task();
                $task->fill($input);
                $task->save();

                if($user->channel->id != $stream->channel_id)
                {
                    Transaction::create($data = [
                        'task_id' => $task->id,
                        'amount' => $amount,
                        'account_sender_id' => $user->account->id,
                        'account_receiver_id' => $task->stream->user->account->id,
                        'status' => TransactionStatus::Holding,
                        'type' => TransactionType::Donation
                    ]);
                }

                return $task;
            });
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }

        TaskResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
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
     * @bodyParam status integer Status of task.
     * @bodyParam small_text text Short description.
     * @bodyParam full_text text Full description.
     * @bodyParam interval_time integer Time for finishing the task. 0 means until the end of the stream.
     * @bodyParam is_superbowl boolean Select superbowl or not.
     * @bodyParam tags Additional tags to task.
     */
    public function update(TaskRequest $request, Stream $stream, Task $task)
    {
        $user = auth()->user();
        $status = $request->has('status') ? $request->get('status') : -1;

        if(!$stream->tasks()->where('id', $task->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks.failed_not_belong_to_stream')], 422);

        //only streamer can make task to work or allow to vote (task done) if stream active
        if($stream->status==StreamStatus::Active && $user->id==$stream->user->id && $status>-1)
        {
            if($task->status==TaskStatus::Created && $status==TaskStatus::Active)
            {
                $task->update(['status' => $status]);
            } else if($task->status==TaskStatus::Active && $status==TaskStatus::AllowVote) {
                $task->update([
                    'status' => $status,
                    'start_active' => Carbon::now('UTC')
                ]);
            }else{
                return response()->json(['error' => trans('api/streams/tasks.failed_change_to_another_status')], 422);
            }
        }
        //Owner of task can change if stream active or just created
        else if($task->status==TaskStatus::Created && $task->user_id==$user->id && in_array($stream->status, [StreamStatus::Active, StreamStatus::Created]))
        {
            $task->update($request->only(['small_text', 'interval_time', 'full_text', 'is_superbowl']));
        }else{
            return response()->json(['error' => trans('api/streams/tasks.failed_cannot_change_info')], 422);
        }

        TaskResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'message'=> trans('api/streams/task.success_updated')
        ], 200);
    }
}
