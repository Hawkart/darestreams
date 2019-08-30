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

        if($task->user_id != $stream->user_id)
        {
            $vote = Vote::firstOrCreate([
                'user_id' => $task->user_id,
                'task_id' => $task->id
            ]);
            $vote->save();

            $thread = $task->stream->threads[0];
            $thread->setParticipant();
        }
    }
}
