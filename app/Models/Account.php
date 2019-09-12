<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactionsSent() {
        return $this->hasMany(Transaction::class, 'account_sender_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactionsReceived() {
        return $this->hasMany(Transaction::class, 'account_sender_id');
    }

    public function relatedUserRelations() {
        return $this->hasMany(Transaction::class, 'account_receiver_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getTransactions()
    {
        return Transaction::where('account_sender_id', $this->id)
            ->orWhere('account_receiver_id', $this->id);
    }
}
