<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Spatie\Tags\HasTags;

class Stream extends Model
{
    use HasTags;

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
    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->morphTomany(Thread::class, 'threadable');
    }
}
