<?php

namespace App\Models;

use App\Enums\StreamStatus;
use App\Http\Requests\ChannelRequest;
use Illuminate\Database\Eloquent\Model;
use \Spatie\Tags\HasTags;
use \Znck\Eloquent\Traits\BelongsToThrough;
use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;

class Stream extends Model implements ViewableContract
{
    use HasTags, BelongsToThrough, Viewable;

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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'start_at', 'ended_at'];

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

        if($this->status==StreamStatus::Active)
        {
            if($this->allow_task_when_stream)
            {
                if($user->account->amount<$this->min_amount_task_when_stream)
                    abort(
                        response()->json(['message' => trans('api/streams/task.not_enough_money')], 402)
                    );
            }else{
                return setErrorAfterValidation(['status' => trans('api/streams/task.not_allow_create_task_when_stream_active')]);
            }
        }
        else if($this->status==StreamStatus::Created)
        {
            if($this->allow_task_before_stream)
            {
                if($user->account->amount<$this->min_amount_task_before_stream)
                    abort(
                        response()->json(['message' => trans('api/streams/task.not_enough_money')], 402)
                    );
            }else{
                return setErrorAfterValidation(['status' => trans('api/streams/task.not_allow_create_task_when_stream_active')]);
            }
        }else{
            return setErrorAfterValidation(['status' => trans('api/streams/task.stream_finished')]);
        }
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
}
