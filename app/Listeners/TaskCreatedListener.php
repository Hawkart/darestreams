<?php

namespace App\Listeners;

use App\Events\TaskCreatedEvent;
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
        $stream = $task->stream;
        $uids = $task->votes()->pluck('user_id')->toArray();

        // Добавить запись в Votes
        if(!in_array($task->user_id, $uids) && $task->user_id != $stream->user_id)
        {
            $vote = new Vote([
                'user_id' => $task->user_id,
                'task_id' => $task->id
            ]);

            $task->votes()->save($vote);

            $thread = $task->stream->threads[0];
            $thread->setParticipant();
        }
    }
}
