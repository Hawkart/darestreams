<?php

namespace App\Models;

use Cmgmyr\Messenger\Models\Message as MMessage;

class Message extends MMessage
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
