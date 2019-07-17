<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Threadable extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'threadables';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function threadable()
    {
        return $this->morphTo();
    }
}
