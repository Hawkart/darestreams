<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Task;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClearDroppedTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:clear_dropped';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear transactions which connected with paypal but not finished.';

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

        $after = Carbon::now('UTC')->subDays(3);

        Transaction::whereDate('created_at', '>', $after)
            ->where('status', TransactionStatus::Created)
            ->where('type', TransactionType::Deposit)
            ->delete();

        $bar->finish();
    }
}
