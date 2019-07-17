<?php

namespace App\Listeners;

use App\Events\StreamCreatedEvent;
use App\Models\Thread;

class StreamCreatedListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\StreamCreatedEvent $event
     * @return mixed
     */
    public function handle(StreamCreatedEvent $event)
    {
        $stream = $event->stream;

        $thread = Thread::create([
            'subject' => "Stream #".$stream->id,
        ]);

        if($thread->id>0)
        {
            $stream->threads()->attach($thread->id);
            //Todo: Add repliers as a participants
        }
    }
}
