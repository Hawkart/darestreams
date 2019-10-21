<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\VoteStatus;
use App\Events\SocketOnDonate;
use App\Http\Requests\TaskTransactionRequest;
use App\Http\Requests\TaskVoteRequest;
use App\Http\Resources\StreamResource;
use App\Models\AdvTask;
use App\Models\Stream;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use DB;
use App\Enums\StreamStatus;
use App\Http\Requests\TaskRequest;
use Carbon\Carbon;

/**
 * @group Tasks
 */
class TaskController extends Controller
{
    /**
     * ChannelController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['setVote', 'update', 'store', 'donate']);
    }

    /**
     * Display a listing of the resource.
     *
     * @queryParam stream_id integer required
     * @queryParam include string String of connections: user, stream, transactions. Example: user,stream
     * @queryParam sort string Sort items by fields: amount_donations, id. For desc use '-' prefix. Example: -amount_donations
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Task::class)
            ->where('stream_id', $request->get('stream_id'))
            ->defaultSort('amount_donations')
            ->allowedSorts(['amount_donations', 'id'])
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

        TaskResource::withoutWrapping();

        return new TaskResource($item);
    }

    /**
     * Create new task for stream.
     *
     * @authenticated
     *
     * @bodyParam stream_id integer required Stream id.
     * @bodyParam small_text text required Short description.
     * @bodyParam full_text text required Full description.
     * @bodyParam interval_time integer required Time for finishing the task. 0 means until the end of the stream.
     * @bodyParam is_superbowl boolean Select superbowl or not.
     * @bodyParam tags Additional tags to task.
     *
     */
    public function store(TaskRequest $request)
    {
        $user = auth()->user();
        $stream = Stream::findOrFail($request->get('stream_id'));
        $adv_task_id = $request->has('adv_task_id') ? $request->get('adv_task_id') : 0;

        if($adv_task_id>0)
        {
            $advTask = AdvTask::findOrFail($adv_task_id);

            try {
                $task = DB::transaction(function () use ($user, $stream, $advTask) {
                    return Task::create([
                        'stream_id' =>  $stream->id,
                        'user_id' => $user->id,
                        'adv_task_id' => $advTask->id,
                        'small_desc' => $advTask->small_desc,
                        'full_desc' => $advTask->full_desc,
                        'status' => TaskStatus::Active
                    ]);
                });

            } catch (\Exception $e) {
                return response($e->getMessage(), 422);
            }
        } else {
            $isStreamer = $user->ownerOfChannel($stream->channel_id);
            $minDonate = $stream->getDonateCreateAmount();
            $input = $request->except('adv_task_id');
            $input['user_id'] = $user->id;
            $input['min_donation'] = $minDonate;

            //If is streamer
            if($isStreamer)
            {
                $input['created_amount'] = 0;
                $input['status'] = TaskStatus::Active;
                $input['start_active'] = Carbon::now('UTC');
            }

            try {
                $task = DB::transaction(function () use ($input, $user, $stream, $isStreamer) {

                    $task = new Task();
                    $task->fill($input);
                    $task->save();

                    if(!$isStreamer && $input['created_amount']>0)
                    {
                        Transaction::create($data = [
                            'task_id' => $task->id,
                            'amount' => $input['created_amount'],
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
        }

        TaskResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'message'=> trans('api/task.success_created')
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
    public function update(TaskRequest $request, Task $task)
    {
        $user = auth()->user();
        $status = $request->has('status') ? $request->get('status') : -1;
        $stream = $task->stream;

        if($task->adv_task_id>0 && $user->ownerOfChannel($stream->channel_id))
        {
            if($stream->status==StreamStatus::Active || $stream->status==StreamStatus::Created)
            {
                if(
                    ($task->status==TaskStatus::Active && $status==TaskStatus::AllowVote) ||
                    (($task->status==TaskStatus::Active || $task->status==TaskStatus::Created) &&  $status==TaskStatus::Canceled)
                )
                {
                    $task->update(['status' => $status]);
                }
            }
        }else{
            if($user->ownerOfChannel($stream->channel_id))
            {
                if($stream->status==StreamStatus::Active )
                {
                    //active->allowVote || ->canceled
                    if($task->status==TaskStatus::Active && $status==TaskStatus::AllowVote || $status==TaskStatus::Canceled)
                    {
                        $task->update(['status' => $status]);
                    }

                    //Owner of task
                    if($task->status==TaskStatus::Created && $task->user_id==$user->id  && ($status==-1 || $task->status==$status))
                    {
                        $task->update($request->only(['small_text', 'interval_time', 'full_text', 'is_superbowl']));
                    }

                    //created -> active
                    if($task->status==TaskStatus::Created && $status==TaskStatus::Active)
                    {
                        $task->update([
                            'status' => $status,
                            'start_active' => Carbon::now('UTC')
                        ]);
                    }
                }
                else if($stream->status==StreamStatus::Created)
                {
                    if($status==TaskStatus::Canceled)
                    {
                        $task->update(['status' => $status]);
                    }

                    //Owner of task
                    if($task->status==TaskStatus::Created && $task->user_id==$user->id && ($status==-1 || $task->status==$status))
                    {
                        $task->update($request->only(['small_text', 'interval_time', 'full_text', 'is_superbowl']));
                    }

                }else{
                    return setErrorAfterValidation(['status' => trans('api/task.failed_change_to_another_status')]);
                }
            }
            //Owner of task can change if stream active or just created
            else if($task->status==TaskStatus::Created && $task->user_id==$user->id && in_array($stream->status, [StreamStatus::Active, StreamStatus::Created]))
            {
                $task->update($request->only(['small_text', 'interval_time', 'full_text', 'is_superbowl']));
            }else{
                return setErrorAfterValidation(['status' => trans('api/task.failed_cannot_change_info')]);
            }
        }

        TaskResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'message'=> trans('api/task.success_updated')
        ], 200);
    }

    /**
     * Set vote for task.
     *
     * {task} - integer id of task.
     *
     * @authenticated
     *
     * @bodyParam vote int Vote parameter, 1-Yes, 2-No, 0 - Pending.
     *
     * @param Task $task
     * @param TaskVoteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setVote(Task $task, TaskVoteRequest $request)
    {
        $user = auth()->user();
        $vote = $task->votes()->where('user_id', $user->id)->first();

        try {
            DB::transaction(function () use ($vote, $request, $task, $user) {
                $vote->update($request->only('vote'));

                if($task->adv_task_id>0)
                {
                    $amount = $task->advTask->price;
                }else{
                    $amount = Transaction::where('task_id', $task->id)
                        ->where('account_sender_id', $user->account->id)
                        ->whereIn('status', [TransactionStatus::Completed, TransactionStatus::Holding])
                        ->sum('amount');
                }

                $data = [];
                if($request->get('vote')==VoteStatus::Yes){
                    $data['vote_yes'] = $task->vote_yes + $amount;
                }else{
                    $data['vote_no'] = $task->vote_no + $amount;
                }

                //change task status if all voted
                $task->refresh();
                if($task->votes()->where('vote', VoteStatus::Pending)->count()==0)
                {
                    $data['status'] = TaskStatus::VoteFinished;
                    $task->update($data);
                }else{
                    $task->update($data);
                    $task->stream->socketInit();
                }

            });
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }

        return response()->json([
            'success' => true,
            'message'=> trans('api/task.vote_accepted')
        ], 200);
    }

    /**
     * Donate
     *
     * @param Task $task
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function donate(Task $task, TaskTransactionRequest $request)
    {
        $data = [
            'task_id' => $task->id,
            'amount' => $request->get('amount'),
            'account_sender_id' => auth()->user()->account->id,
            'account_receiver_id' => $task->stream->user->account->id,
            'status' => TransactionStatus::Holding,
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
            'message'=> trans('api/task.donate_success_created')
        ], 200);
    }

    /**
     * Get list of statuses
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statuses()
    {
        return response()->json(TaskStatus::getInstances(), 200);
    }
}
