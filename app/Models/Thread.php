<?php

namespace App\Models;

use Cmgmyr\Messenger\Models\Thread as MThread;
use Carbon\Carbon;

class Thread extends MThread
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function threadable()
    {
        return $this->belongsTo(Threadable::class, 'thread_id');
    }

    public function setParticipant($user = null)
    {
        if(!$user && !empty(auth()->user()))
            $user = auth()->user();

        if($user)
        {
            //Add new $participant
            $participant = Participant::firstOrCreate([
                'thread_id' => $this->id,
                'user_id' => $user->id,
            ]);
            $participant->last_read = new Carbon;
            $participant->save();
        }
    }
}
