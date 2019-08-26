<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\Transaction;
use App\Models\Vote;
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

        $tasks = Task::where('status', Task::STATUS_FINISHED)
                        ->where('check_vote', Task::VOTE_NOT_YET)->get();

        foreach($tasks as $task)
        {
            $result = 0;
            $votes = Vote::where('task_id', $task->id)->get();

            foreach($votes as $vote)
            {
                if($vote->vote==Vote::VOTE_YES)
                    $result++;

                if($vote->vote==Vote::VOTE_NO)
                    $result--;
            }

            try {
                DB::transaction(function () use ($task, $result)
                {
                    if($result>0)
                    {
                        $transactions = $task->transactions;
                        foreach($transactions as $transaction)
                        {
                            if($transaction->status!=Transaction::PAYMENT_COMPLETED && $transaction->status!=Transaction::PAYMENT_CANCELED)
                                $transaction->update(['status' => Transaction::PAYMENT_COMPLETED]);
                        }
                    }

                    $task->update([
                        'check_vote' => Task::VOTE_FINISHED
                    ]);
                });
            } catch (\Exception $e) {
                return response($e->getMessage(), 422);
            }
        }

        $bar->finish();
    }
}
