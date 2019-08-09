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
}
