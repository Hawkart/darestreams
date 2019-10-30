<?php

use App\Models\Stream;
use Illuminate\Support\Facades\Log;


Broadcast::channel('users.{id}', function ($user, $id) {

    Log::info('Broadcast users', [
        'user' => $user
    ]);
    
    return (int) $user->id === (int) $id;
});

/*
Broadcast::channel('channel_{thread_id}', function ($user, $thread_id) {
    $thread = Thread::findOrFail($thread_id);
    return in_array((int) $user->id, $thread->participantsUserIds());
});
*/

Broadcast::channel('streams.{stream}', function($user, Stream $stream) {
    return true;
});