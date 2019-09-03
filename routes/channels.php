<?php

use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Support\Facades\Log;
use App\Models\Stream;
use \App\Models\Account;

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
    return true;
});

Broadcast::channel('accounts.{account}', function ($user, Account $account) {
    return (int) $user->id === (int) $account->user_id;
});