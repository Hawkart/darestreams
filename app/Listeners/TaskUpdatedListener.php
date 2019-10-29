<?php

namespace App\Listeners;

use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
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
            if($task->status==TaskStatus::Canceled)
                $task->transactions()->update(['status' => TransactionStatus::Canceled]);

            $task->stream->socketInit();

            if($task->status==TaskStatus::IntervalFinishedAllowVote)
                $task->socketPrivateInit();
        }
    }
}