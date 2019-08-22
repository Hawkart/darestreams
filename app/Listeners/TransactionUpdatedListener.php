<?php

namespace App\Listeners;

use App\Events\TransactionUpdatedEvent;
use App\Models\Vote;

class TransactionUpdatedListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\TransactionCreatedEvent $event
     * @return mixed
     */
    public function handle(TransactionUpdatedEvent $event)
    {
        $transaction = $event->transaction;

        if($transaction->isDirty('status'))
        {
            //  Money through PayPal. How to detect? -> Reciever change amount.
            //  Was hold

            //$transaction->getOriginal('status');
        }
    }
}
