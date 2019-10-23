<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rating\Channel;

class Inquire extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inquires';

    protected $fillable = [
        'title', 'name', 'phone', 'email', 'channel_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}