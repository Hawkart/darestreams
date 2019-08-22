<?php

namespace App\Listeners;

use App\Events\TransactionUpdatedEvent;
use App\Models\Transaction;
use App\Models\Vote;

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
            if($transaction->status==Transaction::PAYMENT_COMPLETED)
            {
                //pending,canceled->completed
                if($transaction->getOriginal('status')!=Transaction::PAYMENT_HOLDING)
                {
                    //Update sender's account amount
                    if(intval($transaction->account_sender_id)>0)
                    {
                        $account = $transaction->accountSender;
                        $account->update([
                            "amount" => sumAmounts($account->amount, (-1)*$transaction->amount, 2)
                        ]);
                    }
                }

                //Update receiver's account amount
                $account = $transaction->accountReceiver;
                $account->update([
                    "amount" => sumAmounts($account->amount, $transaction->amount, 2)
                ]);

                if(intval($transaction->task_id)>0)
                {
                    $task = $transaction->task;
                    $task->update([
                        "amount_donations" => sumAmounts($task->amount_donations, $transaction->amount, 2)
                    ]);

                    $stream = $task->steam;
                    $stream->update([
                        'quantity_donations' => intval($stream->quantity_donations) + 1,
                        "amount_donations" => sumAmounts($task->amount_donations, $transaction->amount, 2)
                    ]);
                }

            }else if($transaction->status==Transaction::PAYMENT_CANCELED)
            {
                //holding -> canceled
                if($transaction->getOriginal('status')==Transaction::PAYMENT_HOLDING)
                {
                    //Update sender's account amount
                    if(intval($transaction->account_sender_id)>0)
                    {
                        $account = $transaction->accountSender;
                        $account->update([
                            "amount" => sumAmounts($account->amount, $transaction->amount, 2)
                        ]);
                    }
                }

                //completed -> canceled
                if($transaction->getOriginal('status')!=Transaction::PAYMENT_COMPLETED)
                {
                    //Update receiver's account amount
                    $account = $transaction->accountReceiver;
                    $account->update([
                        "amount" => sumAmounts($account->amount, (-1)*$transaction->amount, 2)
                    ]);
                }

                if(intval($transaction->task_id)>0)
                {
                    $task = $transaction->task;
                    $task->update([
                        "amount_donations" => sumAmounts($task->amount_donations, (-1)*$transaction->amount, 2)
                    ]);

                    $stream = $task->steam;
                    $stream->update([
                        'quantity_donations' => intval($stream->quantity_donations) - 1,
                        "amount_donations" => sumAmounts($task->amount_donations, (-1)*$transaction->amount, 2)
                    ]);
                }
            }
        }
    }
}
