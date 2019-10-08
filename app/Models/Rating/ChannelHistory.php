<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class ChannelHistory extends Model
{
    use HasEagerLimit;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stat_channel_history';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}