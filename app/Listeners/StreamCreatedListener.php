<?php

namespace App\Listeners;

use App\Events\StreamCreatedEvent;
use App\Models\Thread;
use App\Notifications\NotifyFollowersAboutStream;

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
            $thread->setParticipant();
        }

        //Notify all followers
        $user = $stream->user;
        foreach($user->followers()->get() as $follower)
        {
            $details = [
                'greeting' => 'Hi '.$follower->name,
                'body' => 'The stream will start at '.$stream->start_at->addHours(3)->format('d.m.Y h:i'),
                'actionText' => 'View new stream',
                'actionURL' => url('/stream/'.$stream->id),
                'subject' => 'New stream of '.$user->nickname
            ];

            $when = now()->addSeconds(30);
            $follower->notify((new NotifyFollowersAboutStream($details))->delay($when));
        }
    }
}
