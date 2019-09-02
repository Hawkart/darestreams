<?php

namespace App\Http\Controllers\Api\Threads;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\MessageRequest;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Models\Thread;
use App\Models\Message;
use App\Http\Resources\MessageResource;
use App\Events\MessageSent;
use Carbon\Carbon;
use App\Models\Participant;

/**
 * @group Threads messages
 */
class MessageController extends Controller
{
    /**
     * MessageController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store']);
    }

    /**
     * Get messages of thread.
     *
     * @queryParam include string String of connections: user, thread. Example: user
     *
     * @param Request $request
     * @param Thread $thread
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, Thread $thread)
    {
        $query = $thread->messages()->getQuery();

        $items = QueryBuilder::for($query)
            ->allowedIncludes(['user', 'thread'])
            ->jsonPaginate();

        return MessageResource::collection($items);
    }

    /**
     * Detail message of thread.
     * {thread} - thread integer id.
     *
     * @queryParam include string String of connections: user, thread. Example: user
     *
     * @param Thread $thread
     * @param Message $message
     * @return MessageResource|\Illuminate\Http\JsonResponse
     */
    public function show(Thread $thread, Message $message)
    {
        if(!$thread->messages()->where('id', $message->id)->exists())
            return setErrorAfterValidation(['id' => trans('api/threads/message.failed_not_belong_to_thread')]);

        $item = QueryBuilder::for(Message::whereId($message->id))
            ->allowedIncludes(['user', 'thread'])
            ->first();

        return new MessageResource($item);
    }

    /**
     * Create new message for thread.
     *
     * @bodyParam body text Message text.
     *
     * @param MessageRequest $request
     * @param Thread $thread
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(MessageRequest $request, Thread $thread)
    {
        $input = $request->all();
        $user = auth()->user();

        if(!in_array($user->id, $thread->participantsUserIds()))
            return setErrorAfterValidation(['id' => trans('api/threads/message.failed_cannot_write_to_thread')]);

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'body' => $input['body'],
        ]);

        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        //Mark all messages of chat read
        $thread->markAsRead($user->id);

        // Dispatch an event. Will be broadcasted over Redis.
        event(new MessageSent($thread->id, $message));

        return response()->json([], 200);
    }
}