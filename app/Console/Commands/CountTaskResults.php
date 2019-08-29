<?php

namespace App\Console\Commands;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\VoteStatus;
use App\Models\Stream;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CountTaskResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:count_result';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count results for task completed.';

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
                    ->get();

        if(count($streams)>0)
        {
            foreach($streams as $stream)
            {
                $tasks = $stream->tasks()->where('status', TaskStatus::VoteFinished)->with(['votes'])->get();

                if(count($tasks)>0)
                {
                    foreach($tasks as $task)
                    {
                        $result = 0;
                        //$votes = Vote::where('task_id', $task->id)->get();
                        $votes = $task->votes;

                        //Todo: Formula + result to field
                        foreach($votes as $vote)
                        {
                            if($vote->vote==VoteStatus::Yes)
                                $result++;

                            if($vote->vote==VoteStatus::No)
                                $result--;
                        }

                        //Transfer money according to votes
                        try {
                            DB::transaction(function () use ($task, $result)
                            {
                                if($result>0)
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

                    //check all tasks voted then stream to payed
                    if(Task::where('stream_id', $stream->id)->where('status', '<>', TaskStatus::PayFinished)->count()==0)
                        $stream->update(['status' => StreamStatus::FinishedIsPayed]);
                }
            }
        }

        $bar->finish();
    }
}
