<?php

namespace App\Listeners;

use App\Enums\VoteStatus;
use App\Events\SocketOnDonate;
use App\Events\TaskCreatedEvent;
use App\Http\Resources\StreamResource;
use App\Models\AdvTask;
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

        if($task->adv_task_id>0)
        {
            $advTask = AdvTask::findOrFail($task->adv_task_id);

            $campaign = $advTask->campaign;

            $advTask->update([
                'used_amount' => $advTask->used_amount + $advTask->price
            ]);

            $campaign->update([
                'used_amount' => $campaign->used_amount + $advTask->price
            ]);

            Vote::create([
                'user_id' => $campaign->user_id,
                'task_id' => $task->id
            ]);

            $task->stream->socketInit();

        }else{
            $vdata = [
                'user_id' => $task->user_id,
                'task_id' => $task->id
            ];

            //because 0 transaction
            if($task->user_id==$task->stream->user->id)
            {
                $vdata['vote'] = VoteStatus::Yes;

                $task->stream->socketInit();
            }

            Vote::create($vdata);
        }

        $thread = $task->stream->threads[0];
        $thread->setParticipant();
    }
}
