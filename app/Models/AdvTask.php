<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvTask extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adv_tasks';

    /**
     * @var array
     */
    protected $fillable = [
        'campaign_id', 'small_desc', 'full_desc', 'limit', 'price', 'type', 'min_rating'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(AdvCampaign::class, 'campaign_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'adv_task_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function streams()
    {
        return $this->hasManyThrough('App\Models\Stream', 'App\Models\Task');
    }
}