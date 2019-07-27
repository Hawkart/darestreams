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

class MessageController extends Controller
{
    /**
     * @param Request $request
     * @param Thread $thread
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
     * @param Thread $thread
     * @param Message $message
     */
    public function show(Thread $thread, Message $message)
    {
        //Todo: Create lang file and change trans
        if(!$thread->messages()->where('id', $message->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.task_not_belong_to_stream')], 403);

        $item = QueryBuilder::for(Message::whereId($message->id))
            ->allowedIncludes(['user', 'thread'])
            ->first();

        return new MessageResource($item);
    }

    /**
     * @param Request $request
     * @param Thread $thread
     */
    public function store(MessageRequest $request, Thread $thread)
    {
        //Todo: Check can user send the message in this chat
        $input = $request->all();
        $user = auth()->user();

        // $thread->activateAllParticipants();

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

    /**
     * @param Request $request
     * @param Thread $thread
     * @param Message $message
     */
    public function update(MessageRequest $request, Thread $thread, Message $message)
    {

    }
}
