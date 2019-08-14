<?php

namespace App\Models;

use Cmgmyr\Messenger\Models\Thread as MThread;
use Carbon\Carbon;

class Thread extends MThread
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function threadable()
    {
        return $this->belongsTo(Threadable::class, 'thread_id');
    }

    public function setParticipant()
    {
        //Add new $participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $this->id,
            'user_id' => auth()->user()->id,
        ]);
        $participant->last_read = new Carbon;
        $participant->save();
    }
}
