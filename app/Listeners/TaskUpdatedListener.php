<?php

namespace App\Listeners;

use App\Enums\TaskStatus;
use App\Events\SocketOnDonate;
use App\Events\TaskUpdatedEvent;
use App\Http\Resources\StreamResource;

class TaskUpdatedListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskUpdatedEvent $event
     * @return mixed
     */
    public function handle(TaskUpdatedEvent $event)
    {
        $task = $event->task;

        if($task->isDirty('status'))
        {
            $task->stream->socketInit();
            /*$stream = $task->stream;
            $stream->load(['user','channel','game','tasks', 'tasks.votes']);
            StreamResource::withoutWrapping();
            event(new SocketOnDonate(new StreamResource($stream)));*/
        }
    }
}
