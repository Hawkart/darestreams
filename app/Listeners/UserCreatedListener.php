<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;

class UserCreatedListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\UserCreatedEvent $event
     * @return mixed
     */
    public function handle(UserCreatedEvent $event)
    {
        $user = $event->user;

        if(!$user->account)
            $user->account()->create([
                'currency' => 'USD'
            ]);
    }
}
