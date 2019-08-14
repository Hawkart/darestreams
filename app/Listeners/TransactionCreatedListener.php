<?php

namespace App\Listeners;

use App\Events\TransactionCreatedEvent;
use App\Models\Vote;

class TransactionCreatedListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\TransactionCreatedEvent $event
     * @return mixed
     */
    public function handle(TransactionCreatedEvent $event)
    {
        $transaction = $event->transaction;

        if($transaction->task_id>0)
        {
            //Обновляем сумма данатов таск
            $task = $transaction->task;
            $task->update([
                "amount_donations" => $task->amount_donations + $transaction->amount
            ]);

            //Обновляем сумму аккаунта отправителя
            $account = $transaction->accountSender;
            $account->update([
                "amount" => $account->amount + $transaction->amount
            ]);

            // Добавить запись в Votes
            $stream = $task->stream;
            $user = $account->user;
            $uids = $task->votes()->pluck('user_id')->toArray();

            if(!in_array($user->id, $uids) && $user->id != $stream->user_id)
            {
                $vote = new Vote([
                    'user_id' => $user->id,
                    'task_id' => $task->id
                ]);

                $task->votes()->save($vote);
            }

            $thread = $task->stream->threads[0];
            $thread->setParticipant();

        }else{
            //Обновляем сумму аккаунта отправителя
            $account = $transaction->accountSender;
            $account->update([
                "amount" => $account->amount + $transaction->amount
            ]);
        }
    }
}
