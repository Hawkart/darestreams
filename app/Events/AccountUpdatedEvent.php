<?php

namespace App\Events;

use App\Models\Account;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AccountUpdatedEvent
{
    use Dispatchable, SerializesModels;

    public $account;

    /**
     * AccountUpdatedEvent constructor.
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
}