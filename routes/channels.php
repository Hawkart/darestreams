<?php

use App\Models\Stream;


Broadcast::channel('users.{id}', function ($user, $id) {
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