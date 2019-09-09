<?php

namespace App\Listeners;

use App\Enums\VoteStatus;
use App\Events\SocketOnDonate;
use App\Events\TaskCreatedEvent;
use App\Http\Resources\StreamResource;
use App\Models\Vote;

class TaskCreatedListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskCreatedEvent $event
     * @return mixed
     */
    public function handle(TaskCreatedEvent $event)
    {
        $task = $event->task;

        $vdata = [
            'user_id' => $task->user_id,
            'task_id' => $task->id
        ];

        if($task->user_id==$task->stream->user->id)
        {
            $vdata['vote'] = VoteStatus::Yes;

            $task->stream->socketInit();

            /*$stream = $task->stream;
            $stream->load(['user','channel','game','tasks', 'tasks.votes']);
            StreamResource::withoutWrapping();
            event(new SocketOnDonate(new StreamResource($stream)));*/
        }

        Vote::create($vdata);

        $thread = $task->stream->threads[0];
        $thread->setParticipant();
    }
}
