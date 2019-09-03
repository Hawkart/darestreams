<?php

use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Support\Facades\Log;
use App\Models\Stream;

/*
Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('channel_{thread_id}', function ($user, $thread_id) {
    $thread = Thread::findOrFail($thread_id);
    return in_array((int) $user->id, $thread->participantsUserIds());
});
*/

Broadcast::channel('streams.{stream}', function($user, Stream $stream) {
    Log::info('Stream socket on donate in routes', [
        'stream' => $stream,
        'file' => __FILE__,
        'line' => __LINE__
    ]);
    return true;
});