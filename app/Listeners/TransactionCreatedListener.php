<?php

namespace App\Listeners;

use App\Events\TransactionCreatedEvent;
use App\Models\Transaction;
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

        if($transaction->status==Transaction::PAYMENT_COMPLETED)
        {
            //Update receiver's account amount
            $account = $transaction->accountReceiver;
            $account->update([
                "amount" => sumAmounts($account->amount, $transaction->amount, 2)
            ]);

            //Update sender's account amount
            if(intval($transaction->account_sender_id)>0)
            {
                $account = $transaction->accountSender;
                $account->update([
                    "amount" => sumAmounts($account->amount, (-1)*$transaction->amount, 2)
                ]);
            }
        }else if($transaction->status==Transaction::PAYMENT_HOLDING){

            //Update sender's account amount
            if(intval($transaction->account_sender_id)>0)
            {
                $account = $transaction->accountSender;
                $account->update([
                    "amount" => sumAmounts($account->amount, (-1)*$transaction->amount, 2)
                ]);
            }

            if(intval($transaction->task_id)>0)
            {
                $task = $transaction->task;
                $task->update([
                    "amount_donations" => sumAmounts($task->amount_donations, $transaction->amount, 2)
                ]);
            }
        }

        //Add to votes and to chat
        if(intval($transaction->task_id)>0)
        {
            $stream = $task->stream;
            $user = $transaction->accountSender->user;

            $uids = $task->votes()->pluck('user_id')->toArray();
            if (!in_array($user->id, $uids) && $user->id != $stream->user_id) {
                $vote = new Vote([
                    'user_id' => $user->id,
                    'task_id' => $task->id
                ]);

                $task->votes()->save($vote);
            }

            //$thread = $stream->threads[0];
            //
            //$thread->setParticipant();
        }
    }
}
