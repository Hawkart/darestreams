<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
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
    protected $fillable = ['user_id', 'start_at', 'end_at', 'times', 'comment'];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'start_at', 'finished_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        $now = Carbon::now('UTC');
        return $query->where('start_at', '<', $now)
                        ->where('end_at', '>', $now);
    }
}