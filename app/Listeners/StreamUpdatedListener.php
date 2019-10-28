<?php

namespace App\Listeners;

use App\Enums\StreamStatus;
use App\Events\StreamUpdatedEvent;
use App\Jobs\SyncStreamByTwitch;
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
        }
    }
}
