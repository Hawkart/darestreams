<?php

namespace App\Models;

use Cmgmyr\Messenger\Models\Participant as MParticipant;

class Participant extends MParticipant
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
