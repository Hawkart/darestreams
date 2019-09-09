<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Streamer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'streamers';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $casts = [
        'json' => 'array'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}