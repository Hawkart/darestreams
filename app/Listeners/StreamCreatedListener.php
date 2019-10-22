<?php

namespace App\Listeners;

use App\Events\StreamCreatedEvent;
use App\Models\Thread;
use App\Notifications\NotifyFollowersAboutStream;
use Illuminate\Support\Facades\Log;

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
        if(count($user->followers)>0)
        {
            foreach($user->followers as $follower)
            {
                $details = [
                    'greeting' => 'Hi '.$follower->name,
                    'body' => 'The stream will start at '.$stream->start_at->addHours(3)->format('d.m.Y h:i'),
                    'actionText' => 'View new stream',
                    'actionURL' => url('/stream/'.$stream->id),
                    'subject' => 'New stream of '.$user->nickname
                ];

                Log::info('StreamCreatedListener', ['details' => $details, 'file' => __FILE__, 'line' => __LINE__]);

                //$when = now()->addSeconds(30);
                $follower->notify((new NotifyFollowersAboutStream($details)));//->delay($when));
            }
        }

    }
}
