<?php

namespace App\Models;

use App\Http\Requests\ChannelRequest;
use Illuminate\Database\Eloquent\Model;
use \Spatie\Tags\HasTags;
use \Znck\Eloquent\Traits\BelongsToThrough;
use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;

class Stream extends Model implements ViewableContract
{
    use HasTags, BelongsToThrough, Viewable;

    const STATUS_CREATED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_FINISHED = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'streams';

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
        'created' => \App\Events\StreamCreatedEvent::class,
    ];

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
     * @Relation
     */
    public function threads()
    {
        return $this->morphToMany(Thread::class, 'threadable');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function canTaskCreate()
    {
        $user = auth()->user();

        if($this->status==Stream::STATUS_ACTIVE)
        {
            if($this->allow_task_when_stream)
            {
                if($user->account->amount<$this->min_amount_task_when_stream)
                    return response()->json(['error' => trans('api/streams/task.not_enough_money')], 422);

            }else{
                return response()->json(['error' => trans('api/streams/task.not_allow_create_task_when_stream_active')], 422);
            }
        }

        if($this->status==Stream::STATUS_CREATED)
        {
            if($this->allow_task_before_stream)
            {
                if($user->account->amount<$this->min_amount_task_before_stream)
                    return response()->json(['error' => trans('api/streams/task.not_enough_money')], 422);
            }else{
                return response()->json(['error' => trans('api/streams/task.not_allow_create_task_before_stream')], 422);
            }
        }

        if($this->status==Stream::STATUS_FINISHED)
            return response()->json(['error' => trans('api/streams/task.stream_finished')], 422);
    }

    /**
     * @return int|mixed
     */
    public function getTaskCreateAmount()
    {
        $amount = 0;

        if($this->status==Stream::STATUS_ACTIVE)
        {
            $amount = $this->min_amount_task_when_stream;
        }
        else if($this->status==Stream::STATUS_CREATED)
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

        if($this->status==Stream::STATUS_ACTIVE)
        {
            $amount = $this->min_amount_task_when_stream;
        }
        else if($this->status==Stream::STATUS_CREATED)
        {
            $amount = $this->min_amount_task_before_stream;
        }

        return $amount;
    }

    public function canMakeDonate()
    {
        $user = auth()->user();

        if($this->status==Stream::STATUS_ACTIVE)
        {
            if($this->allow_task_when_stream)
            {
                if($user->account->amount<$this->min_amount_task_when_stream)
                    return response()->json(['error' => trans('api/streams/task.not_enough_money')], 422);

            }else{
                return response()->json(['error' => trans('api/streams/task.not_allow_create_task_when_stream_active')], 422);
            }
        }

        if($this->status==Stream::STATUS_CREATED)
        {
            if($this->allow_task_before_stream)
            {
                if($user->account->amount<$this->min_amount_task_before_stream)
                    return response()->json(['error' => trans('api/streams/task.not_enough_money')], 422);
            }else{
                return response()->json(['error' => trans('api/streams/task.not_allow_create_task_before_stream')], 422);
            }
        }

        if($this->status==Stream::STATUS_FINISHED)
            return response()->json(['error' => trans('api/streams/task.stream_finished')], 422);
    }
}
