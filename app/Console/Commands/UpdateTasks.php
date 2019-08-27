<?php

namespace App\Console\Commands;

use App\Models\Stream;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:update_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set stream to active if it\'s time';

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

        $bar->finish();
    }
}
