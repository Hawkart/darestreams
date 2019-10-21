<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AdvCampaign extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adv_campaigns';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'from', 'to', 'title', 'brand', 'logo', 'limit', 'used_amount'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'from', 'to'];

    /**
     * @param $value
     */
    public function setFromAttribute($value)
    {
        $this->attributes['from'] = Carbon::parse($value)->toDateTimeString();
    }

    /**
     * @param $value
     */
    public function setToAttribute($value)
    {
        $this->attributes['to'] = Carbon::parse($value)->toDateTimeString();
    }

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
    public function advTasks()
    {
        return $this->hasMany(AdvTask::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tasks()
    {
        return $this->hasManyThrough('App\Models\Task', 'App\Models\AdvTask', 'campaign_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        $now = Carbon::now('UTC');
        return $query->where('from', '<', $now)->where('to', '>', $now);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeFinished($query)
    {
        return $query->where('to', '<', Carbon::now('UTC'));
    }

    /**
     * @return bool
     */
    public function isStarted()
    {
        return Carbon::parse($this->from)->gt(Carbon::now('UTC'));
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return Carbon::parse($this->to)->lt(Carbon::now('UTC'));
    }
}