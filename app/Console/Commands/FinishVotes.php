<?php

namespace App\Console\Commands;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Enums\VoteStatus;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FinishVotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'votes:finish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finish votes by tasks.';

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

        //Get all tasks in statuses to vote where stream ended 30 minutes ago and not payed
        $after = Carbon::now('UTC')->subMinutes(config('app.time_vote_until_stream_finished'));
        $tasks = Task::whereIn('status', [TaskStatus::IntervalFinishedAllowVote, TaskStatus::AllowVote])
            ->whereHas('stream', function($q) use ($after) {
                $q->where('ended_at', '<', $after)
                    ->whereNotNull('ended_at')
                    ->where('status', StreamStatus::FinishedWaitPay);
            })->get();

        //count result vote, set result and new status to task
        if(count($tasks)>0)
        {
            foreach($tasks as $task)
            {
                $no = $task->votes()->where('vote', VoteStatus::No)->sum('amount_donations');
                $yes = $task->votes()->where('vote', '<>', VoteStatus::No)->sum('amount_donations');

                try {
                    DB::transaction(function () use ($task, $yes, $no)
                    {
                        $task->update([
                            'status' => TaskStatus::VoteFinished,
                            'vote_yes' => $yes,
                            'vote_no' => $no
                        ]);
                    });
                } catch (\Exception $e) {
                    echo response($e->getMessage(), 422);
                }
            }
        }

        $bar->finish();
    }
}
