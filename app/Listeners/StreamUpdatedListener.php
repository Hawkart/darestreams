<?php

namespace App\Listeners;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Events\StreamUpdatedEvent;
use App\Jobs\SyncStreamByTwitch;
use App\Models\Task;
use Carbon\Carbon;

class StreamUpdatedListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\StreamUpdatedEvent $event
     * @return mixed
     */
    public function handle(StreamUpdatedEvent $event)
    {
        $stream = $event->stream;
        $stream->socketInit();

        if($stream->isDirty('status'))
        {
            if($stream->status==StreamStatus::FinishedWaitPay)
            {
                $stream->load(['channel']);
                dispatch(new SyncStreamByTwitch($stream))->delay(Carbon::now('UTC')->addMinutes(5));
            }

            if($stream->status==StreamStatus::Active)
            {
                $stream->tasks()->where('user_id', $stream->channel->user_id)->update([
                    'status' => TaskStatus::Active,
                    'start_active' => Carbon::now('UTC')
                ]);
            }
        }
    }
}
