<?php

namespace App\Listeners;

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

        if($task->user_id==$task->stream->user->id)
        {
            $stream = $task->stream;
            $stream->load(['user','channel','game','tasks', 'tasks.vote']);
            StreamResource::withoutWrapping();
            event(new SocketOnDonate(new StreamResource($stream)));
        }else{
            $vote = Vote::firstOrCreate([
                'user_id' => $task->user_id,
                'task_id' => $task->id
            ]);
            $vote->save();
        }

        $thread = $task->stream->threads[0];
        $thread->setParticipant();
    }
}
