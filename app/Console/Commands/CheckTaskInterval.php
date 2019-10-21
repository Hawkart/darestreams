<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CheckTaskInterval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check_interval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check task interval and change status to vote.';

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

        $now = Carbon::now('UTC');
        $tasks = Task::where('interval_time', '>', 0)
                    ->where('status', TaskStatus::Active)
                    ->get();

        if(count($tasks)>0)
        {
            foreach($tasks as $task)
            {
                if(Carbon::parse($task->start_active)->addMinutes($task->interval_time)->lte($now))
                {
                    $status = TaskStatus::IntervalFinishedAllowVote;

                    if($task->amount_donations==0)
                        $status = TaskStatus::PayFinished;

                    $task->update(['status' => $status]);
                }
            }
        }

        $bar->finish();
    }
}
