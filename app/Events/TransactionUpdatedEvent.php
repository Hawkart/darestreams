<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TransactionUpdatedEvent
{
    use Dispatchable, SerializesModels;

    public $transaction;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }
}
