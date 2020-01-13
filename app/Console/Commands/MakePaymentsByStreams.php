<?php

namespace App\Console\Commands;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\VoteStatus;
use App\Models\AdvTask;
use App\Models\Stream;
use App\Models\Task;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MakePaymentsByStreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streams:pay_donations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send money according all votes by tasks.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $bar = $this->output->createProgressBar(100);

        $after = Carbon::now('UTC')->subMinutes(config('app.time_vote_until_stream_finished'));
        $streams = Stream::where('ended_at', '<', $after)
                    ->whereNotNull('ended_at')
                    ->where('status', StreamStatus::FinishedWaitPay)
                    ->with(['tasks'])
                    ->get();

        $statuses = [TaskStatus::PayFinished, TaskStatus::Canceled, TaskStatus::Created];

        if(count($streams)>0)
        {
            foreach($streams as $stream)
            {
                $tasks = $stream->tasks;

                if(count($tasks)>0)
                {
                    foreach($tasks as $task)
                    {
                        if($task->status==TaskStatus::VoteFinished)
                        {
                            if($task->adv_task_id>0)
                            {
                                $user = $task->stream->user;
                                $advTask = $task->advTask;
                                $advertiser = $advTask->campaign->user;
                                $result = $task->vote_yes-$task->vote_no;

                                try {
                                    DB::transaction(function () use ($task, $advTask, $result, $advertiser, $user)
                                    {
                                        if($result>=0)
                                        {
                                            Transaction::create([
                                                'task_id' => $task->id,
                                                'amount' => $advTask->price,
                                                'account_sender_id' => $advertiser->account->id,
                                                'account_receiver_id' => $user->account->id,
                                                'status' => TransactionStatus::Completed,
                                                'type' => TransactionType::Donation
                                            ]);
                                        }

                                        $task->update(['status' => TaskStatus::PayFinished]);
                                    });
                                } catch (\Exception $e) {
                                    echo response($e->getMessage(), 422);
                                }

                            }else{
                                $result = $task->vote_yes-$task->vote_no;

                                //Transfer money according to votes
                                try {
                                    DB::transaction(function () use ($task, $result)
                                    {
                                        if($result>=0)
                                            $tstatus = TransactionStatus::Completed;
                                        else
                                            $tstatus = TransactionStatus::Canceled;

                                        $transactions = $task->transactions;
                                        foreach($transactions as $transaction)
                                        {
                                            if($transaction->status!=TransactionStatus::Completed && $transaction->status!=TransactionStatus::Canceled)
                                                $transaction->update(['status' => $tstatus]);
                                        }

                                        $task->update([
                                            'status' => TaskStatus::PayFinished,
                                            //'amount_donations' => $result
                                        ]);
                                    });
                                } catch (\Exception $e) {
                                    echo response($e->getMessage(), 422);
                                }
                            }
                        }
                    }
                }

                //check all tasks voted then stream to payed
                if(Task::where('stream_id', $stream->id)->whereNotIn('status', $statuses)->count()==0)
                {
                    try {
                        DB::transaction(function () use ($stream)
                        {
                            $stream->update(['status' => StreamStatus::FinishedIsPayed]);
                        });
                    }catch (\Exception $e) {
                        echo response($e->getMessage(), 422);
                    }
                }
            }
        }

        $bar->finish();
    }
}