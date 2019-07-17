<?php

namespace App\Models;

use Cmgmyr\Messenger\Models\Thread as MThread;

class Thread extends MThread
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function threadable()
    {
        return $this->belongsTo(Threadable::class, 'thread_id');
    }
}
