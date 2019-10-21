<?php

namespace App\Console\Commands\ClearAndDelete;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ClearNotUsedTasksInFinishedStreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:clear_not_used';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear not used tasks.';

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

        $after = Carbon::now('UTC')->subDays(1);

        Task::where('status', TaskStatus::Created)
            ->whereHas('stream', function($q) use ($after) {
                $q->whereDate('created_at', '>', $after)
                    ->whereNotNull('ended_at')
                    ->where('status', StreamStatus::FinishedIsPayed);
            })->delete();

        $bar->finish();
    }
}
