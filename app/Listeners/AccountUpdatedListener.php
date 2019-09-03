<?php

namespace App\Listeners;

use App\Events\AccountOnChange;
use App\Events\AccountUpdatedEvent;
use App\Events\SocketOnDonate;
use App\Http\Resources\AccountResource;
use App\Http\Resources\StreamResource;

class AccountUpdatedListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskUpdatedEvent $event
     * @return mixed
     */
    public function handle(AccountUpdatedEvent $event)
    {
        $account = $event->account;

        if($account->isDirty('amount'))
        {
            AccountResource::withoutWrapping();
            event(new AccountOnChange(new AccountResource($account)));
        }
    }
}
