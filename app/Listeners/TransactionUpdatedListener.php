<?php

namespace App\Listeners;

use App\Enums\TransactionStatus;
use App\Events\TransactionUpdatedEvent;

class TransactionUpdatedListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\TransactionCreatedEvent $event
     * @return mixed
     */
    public function handle(TransactionUpdatedEvent $event)
    {
        $transaction = $event->transaction;

        if($transaction->isDirty('status'))
        {
            if($transaction->status==TransactionStatus::Completed)
            {
                //pending,canceled->completed
                if($transaction->getOriginal('status')!=TransactionStatus::Holding)
                {
                    //Update sender's account amount
                    if(intval($transaction->account_sender_id)>0)
                    {
                        $account = $transaction->accountSender;
                        $account->update([
                            "amount" => $account->amoun-$transaction->amount
                        ]);
                    }
                }

                //Update receiver's account amount
                $account = $transaction->accountReceiver;
                $account->update([
                    "amount" => $account->amount+$transaction->amount
                ]);

                /*
                if(intval($transaction->task_id)>0)
                {
                    $task = $transaction->task;
                    $task->update([
                        "amount_donations" => $task->amount_donations + $transaction->amount
                    ]);

                    $stream = $task->steam;
                    $stream->update([
                        'quantity_donations' => intval($stream->quantity_donations) + 1,
                        "amount_donations" => $task->amount_donations + $transaction->amount
                    ]);
                }
                */

            }else if($transaction->status==TransactionStatus::Canceled)
            {
                //holding -> canceled
                if($transaction->getOriginal('status')==TransactionStatus::Holding)
                {
                    //Update sender's account amount
                    if(intval($transaction->account_sender_id)>0)
                    {
                        $account = $transaction->accountSender;
                        $account->update([
                            "amount" => $account->amount+$transaction->amount
                        ]);
                    }
                }

                //completed -> canceled
                if($transaction->getOriginal('status')!=TransactionStatus::Completed)
                {
                    //Update receiver's account amount
                    $account = $transaction->accountReceiver;
                    $account->update([
                        "amount" => $account->amount-$transaction->amount
                    ]);
                }

                if(intval($transaction->task_id)>0)
                {
                    $task = $transaction->task;
                    $task->update([
                        "amount_donations" => intval($task->amount_donations)-intval($transaction->amount)
                    ]);

                    //holding -> canceled
                    if($transaction->getOriginal('status')==TransactionStatus::Holding)
                    {
                        $stream = $task->steam;
                        $stream->update([
                            'quantity_donations' => intval($stream->quantity_donations) - 1,
                            "amount_donations" => intval($stream->amount_donations) - intval($transaction->amount)
                        ]);
                    }
                }
            }
        }
    }
}
