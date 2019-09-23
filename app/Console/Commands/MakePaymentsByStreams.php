<?php

namespace App\Console\Commands;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Models\Stream;
use App\Models\Task;
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

                                    $task->update(['status' => TaskStatus::PayFinished]);
                                });
                            } catch (\Exception $e) {
                                echo response($e->getMessage(), 422);
                            }
                        }
                    }
                }

                //check all tasks voted then stream to payed
                if(Task::where('stream_id', $stream->id)->whereNotIn('status', $statuses)->count()==0)
                {
                    $stream->update(['status' => StreamStatus::FinishedIsPayed]);

                    dd($stream);
                }

                dd(1);
            }
        }

        $bar->finish();
    }
}