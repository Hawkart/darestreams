<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const PAYMENT_CANCELED = 3;     // Money got back and transaction canceled.
    const PAYMENT_HOLDING = 2;      //Money get from account but not transfered
    const PAYMENT_COMPLETED = 1;    //Money got from account and transfered
    const PAYMENT_PENDING = 0;      //Transaction created but no actions with money. Needs for PayPal payment.

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'json',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'json' => 'array'
    ];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\TransactionCreatedEvent::class,
        'updated' => \App\Events\TransactionUpdatedEvent::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountSender()
    {
        return $this->belongsTo(Account::class, 'account_sender_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountReceiver()
    {
        return $this->belongsTo(Account::class, 'account_receiver_id');
    }
}
