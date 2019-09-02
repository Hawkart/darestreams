<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\VoteStatus;
use App\Http\Requests\TaskTransactionRequest;
use App\Models\Stream;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
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

        $stream = Stream::firstOrFail($request->get('stream_id'));

        $stream->canTaskCreate();
        $amount = $stream->getTaskCreateAmount();
        $minDonate = $stream->getDonateCreateAmount();

        $input = $request->all();
        $input['user_id'] = $user->id;
        $input['min_donation'] = $minDonate;
        $input['status'] = TaskStatus::Created;

        if($input['created_amount']<$amount)
            return setErrorAfterValidation(['created_amount' => trans('api/streams/tasks.not_enough_money')]);

        //If not owner of stream check how much money you have
        if($user->channel->id != $stream->channel_id && $user->account->amount<$amount)
            abort(
                response()->json(['message' => trans('api/streams/tasks.not_enough_money')], 402)
            );

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
    public function update(TaskRequest $request, Task $task)
    {
        $user = auth()->user();
        $status = $request->has('status') ? $request->get('status') : -1;
        $stream = $task->stream;

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
                return setErrorAfterValidation(['status' => trans('api/streams/tasks.failed_change_to_another_status')]);
            }
        }
        //Owner of task can change if stream active or just created
        else if($task->status==TaskStatus::Created && $task->user_id==$user->id && in_array($stream->status, [StreamStatus::Active, StreamStatus::Created]))
        {
            $task->update($request->only(['small_text', 'interval_time', 'full_text', 'is_superbowl']));
        }else{
            return setErrorAfterValidation(['status' => trans('api/streams/tasks.failed_cannot_change_info')]);
        }

        TaskResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'message'=> trans('api/streams/task.success_updated')
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setVote(Task $task, Request $request)
    {
        $user = auth()->user();

        if($task->status!=TaskStatus::AllowVote && $task->status!=TaskStatus::IntervalFinishedAllowVote)
            return setErrorAfterValidation(['status' => trans('api/task.vote_finished')]);

        $votes = $task->votes()->where('user_id', $user->id);

        if($votes->count()==0)
            return setErrorAfterValidation(['status' => trans('api/task.no_vote')]);

        $vote = $votes->first();

        if($vote->vote!=VoteStatus::Pending)
            return setErrorAfterValidation(['status' => trans('api/task.already_vote')]);

        try {
            DB::transaction(function () use ($vote, $request, $task, $user) {
                $vote->update($request->only('vote'));

                $amount = Transaction::where('task_id', $task->id)
                    ->where('account_sender_id', $user->account->id)
                    ->whereIn('status', [TransactionStatus::Completed, TransactionStatus::Holding])
                    ->sum('amount');

                if($request->only('vote')==VoteStatus::Yes){
                    $task->update(['vote_yes' => $task->vote_yes + $amount]);
                }else{
                    $task->update(['vote_no' => $task->vote_no + $amount]);
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
        $user = auth()->user();
        $amount = $request->get('amount');

        if($task->status!=TaskStatus::Active && !($user->id==$task->user_id && $task->status==TaskStatus::Created))
            return setErrorAfterValidation(['status' => trans('api/transaction.failed_task_not_active')]);

        if($amount<$task->min_donation)
            return setErrorAfterValidation(['min_donation' => trans('api/transaction.not_enough_money')]);

        //enough money
        if($amount <= $user->account->amount)
        {
            $data = [
                'task_id' => $task->id,
                'amount' => $request->get('amount'),
                'account_sender_id' => $user->account->id,
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
                'message'=> trans('api/streams/tasks/transaction.success_created')
            ], 200);

        }else{
            abort(
                response()->json(['message' => trans('api/transaction.not_enough_money')], 402)
            );
        }
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
