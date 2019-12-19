<?php

namespace App\Models;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use \Spatie\Tags\HasTags;
use \Znck\Eloquent\Traits\BelongsToThrough;
use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use App\Events\SocketOnDonate;
use App\Http\Resources\StreamResource;

class Stream extends Model implements ViewableContract
{
    use HasTags, BelongsToThrough, Viewable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'streams';

    protected $fillable = [
        'game_id', 'link', 'start_at', 'ended_at', 'status', 'quantity_donators', 'quantity_donations', 'amount_donations',
        'channel_id', 'allow_task_before_stream', 'allow_task_when_stream', 'min_amount_task_before_stream', 'min_amount_task_when_stream',
        'min_amount_donate_task_before_stream', 'min_amount_donate_task_when_stream', 'allow_superbowl_before_stream',
        'allow_superbowl_when_stream', 'min_amount_superbowl_before_stream', 'min_amount_superbowl_when_stream',
        'min_amount_donate_superbowl_before_stream', 'min_amount_donate_superbowl_when_stream', '	goal_amount_donate_superbowl_activate',
        'title', 'tags', 'views', 'preview', 'created_by_system'
    ];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\StreamCreatedEvent::class,
        'updated' => \App\Events\StreamUpdatedEvent::class,
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'start_at', 'ended_at'];

    /**
     * @param $value
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = empty($value) ? StreamStatus::Created : $value;
    }

    /**
     * @param $value
     */
    public function setStartAtAttribute($value)
    {
        $this->attributes['start_at'] = Carbon::parse($value)->toDateTimeString();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsToThrough(User::class, Channel::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasksCompleted()
    {
        return $this->hasMany(Task::class)
                    ->whereIn('status', [TaskStatus::VoteFinished, TaskStatus::PayFinished]);
    }

    /**
     * @Relation
     */
    public function threads()
    {
        return $this->morphToMany(Thread::class, 'threadable');
    }

    /**
     * @return int|mixed
     */
    public function getTaskCreateAmount()
    {
        $amount = 0;

        if($this->status==StreamStatus::Active)
        {
            $amount = $this->min_amount_task_when_stream;
        }
        else if($this->status==StreamStatus::Created)
        {
            $amount = $this->min_amount_task_before_stream;
        }

        return $amount;
    }

    /**
     * @return int|mixed
     */
    public function getDonateCreateAmount()
    {
        $amount = 0;

        if($this->status==StreamStatus::Active)
        {
            $amount = $this->min_amount_task_when_stream;
        }
        else if($this->status==StreamStatus::Created)
        {
            $amount = $this->min_amount_task_before_stream;
        }

        return $amount;
    }

    /**
     * @param $stream
     */
    public function socketInit()
    {
        $this->load(['user','channel','game','tasks', 'tasks.votes']);
        StreamResource::withoutWrapping();
        event(new SocketOnDonate(new StreamResource($this)));
    }

    /**
     * @return bool
     */
    public function checkAlreadyFinished()
    {
        return in_array($this->status, [StreamStatus::FinishedWaitPay, StreamStatus::FinishedIsPayed]);
    }
}
