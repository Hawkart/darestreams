<?php

namespace App\Console\Commands;

use App\Models\Stream;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Image;
use File;

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

        $minus10 = Carbon::now('UTC')->subMinutes(10);
        $streams = Stream::where('ended_at', '<', $minus10)
                    ->whereNotNull('ended_at')
                    ->where('status', Stream::STATUS_FINISHED)
                    ->get();

        if(count($streams)>0)
        {
            foreach($streams as $stream)
            {
                $tasks = $stream->tasks()->where('check_vote', '<>', Task::VOTE_FINISHED)->with(['votes'])->get();

                if(count($tasks)>0)
                {
                    foreach($tasks as $task)
                    {
                        $result = 0;
                        //$votes = Vote::where('task_id', $task->id)->get();
                        $votes = $task->votes;

                        foreach($votes as $vote)
                        {
                            if($vote->vote==Vote::VOTE_YES)
                                $result++;

                            if($vote->vote==Vote::VOTE_NO)
                                $result--;
                        }

                        //Transfer money according to votes
                        try {
                            DB::transaction(function () use ($task, $result)
                            {
                                if($result>0)
                                    $tstatus = Transaction::PAYMENT_COMPLETED;
                                else
                                    $tstatus = Transaction::PAYMENT_CANCELED;

                                $transactions = $task->transactions;
                                foreach($transactions as $transaction)
                                {
                                    if($transaction->status!=Transaction::PAYMENT_COMPLETED && $transaction->status!=Transaction::PAYMENT_CANCELED)
                                        $transaction->update(['status' => $tstatus]);
                                }

                                $task->update([
                                    'check_vote' => Task::VOTE_FINISHED
                                ]);
                            });
                        } catch (\Exception $e) {
                            echo response($e->getMessage(), 422);
                        }
                    }

                    //check all tasks voted then stream to payed
                    if(Task::where('stream_id', $stream->id)->where('check_vote', '<>', Task::VOTE_FINISHED)->count()==0)
                        $stream->update(['status' => Stream::STATUS_FINISHED_AND_PAYED]);
                }
            }
        }

        $bar->finish();
    }
}
