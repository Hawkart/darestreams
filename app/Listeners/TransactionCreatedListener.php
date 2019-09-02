<?php

namespace App\Listeners;

use App\Enums\TransactionStatus;
use App\Events\SocketOnDonate;
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

        //donate to user
        if($transaction->status==TransactionStatus::Completed)
        {
            //Update receiver's account amount
            $account = $transaction->accountReceiver;
            $account->update([
                "amount" => $account->amount+$transaction->amount
            ]);

            //Update sender's account amount
            if(intval($transaction->account_sender_id)>0)
            {
                $account = $transaction->accountSender;
                $account->update([
                    "amount" => $account->amount-$transaction->amount
                ]);
            }
        }else if($transaction->status==TransactionStatus::Holding){  //to task

            //Update sender's account amount
            if(intval($transaction->account_sender_id)>0)
            {
                $account = $transaction->accountSender;
                $account->update([
                    "amount" => $account->amount-$transaction->amount
                ]);
            }
        }else{
            //to himself by paypal
        }

        //Add to votes and to chat
        if(intval($transaction->task_id)>0)
        {
            $task = $transaction->task;
            $stream = $task->stream;
            $user = $transaction->accountSender->user;

            $vote = Vote::firstOrCreate([
                'user_id' => $user->id,
                'task_id' => $task->id
            ]);
            $vote->amount_donations = isset($vote->amount_donations) ? intval($vote->amount_donations)+$transaction->amount : $transaction->amount;
            $vote->save();

            //update task & stream info
            if($transaction->status==TransactionStatus::Completed || $transaction->status==TransactionStatus::Holding)
            {
                $task->update([
                    "amount_donations" => intval($task->amount_donations)+intval($transaction->amount)
                ]);

                $stream = $task->stream;
                $stream->update([
                    'quantity_donations' => intval($stream->quantity_donations) + 1,
                    "amount_donations" => intval($stream->amount_donations)+intval($transaction->amount)
                ]);
            }

            $thread = $stream->threads[0];
            $thread->setParticipant($user);

            $task->load('stream');

            // Dispatch an event. Will be broadcasted over Redis.
            event(new SocketOnDonate($task));
        }
    }
}
