<?php

namespace App\Models;

use App\Models\Rating\GameChannelHistory;
use App\Models\Rating\GameHistory;
use Illuminate\Database\Eloquent\Model;
use \Spatie\Tags\HasTags;

class Game extends Model
{
    use HasTags;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'games';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channels()
    {
        return $this->hasMany(Channel::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(GameHistory::class)->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lastHistory()
    {
        return $this->history()->limit(2);
    }
}