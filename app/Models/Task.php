<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Events\SocketOnTask;
use App\Http\Resources\TaskResource;
use Illuminate\Database\Eloquent\Model;
use \Znck\Eloquent\Traits\BelongsToThrough;

class Task extends Model
{
    use BelongsToThrough;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\TaskCreatedEvent::class,
        'updated' => \App\Events\TaskUpdatedEvent::class,
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'start_active'];

    /**
     * @param $value
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = empty($value) ? TaskStatus::Created : $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advTask()
    {
        return $this->belongsTo(AdvTask::class, 'adv_task_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    /**
     * @return mixed
     */
    public function channel()
    {
        return $this->belongsToThrough(Channel::class, Stream::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'task_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @param $stream
     */
    public function socketPrivateInit()
    {
        $this->load(['stream', 'stream.channel']);
        TaskResource::withoutWrapping();
        event(new SocketOnTask(new TaskResource($this)));
    }
}