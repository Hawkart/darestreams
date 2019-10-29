<?php

namespace App\Listeners;

use App\Events\UserDeletingEvent;
use App\Models\Message;
use App\Models\Participant;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\Vote;
use DB;

class UserDeletingListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\UserDeletingEvent $user
     * @return mixed
     */
    public function handle(UserDeletingEvent $event)
    {
        $user = $event->user;

        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $tsIds = [];
        if($user->channel)
        {
            $stIds = $user->channel->streams->pluck('id')->toArray();
            $tsIds = Task::whereIn('stream_id', $stIds)->pluck('id')->toArray();
        }

        $tsIds = array_merge($tsIds, $user->tasks()->pluck('id')->toArray());

        Transaction::whereIn('task_id', $tsIds)->delete();
        Vote::whereIn('task_id', $tsIds)->delete();
        Task::whereIn('id', $tsIds)->delete();

        Message::where('user_id', $user->id)->forceDelete();
        Participant::where('user_id', $user->id)->forceDelete();

        $user->streams()->delete();
        $user->getTransactions()->delete();
        $user->notifications()->delete();

        $user->account->delete();
        $user->channel->delete();
        $user->oauthProviders()->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();
    }
}