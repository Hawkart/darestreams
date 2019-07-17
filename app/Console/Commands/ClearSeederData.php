<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Game;
use App\Models\Message;
use App\Models\OAuthProvider;
use App\Models\Stream;
use App\Models\Task;
use App\Models\Thread;
use App\Models\Threadable;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Console\Command;
use DB;

class ClearSeederData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seeder:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear seeder data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(100);

        DB::statement("SET foreign_key_checks=0");
        User::truncate();
        Account::truncate();
        Game::truncate();
        OAuthProvider::truncate();
        Stream::truncate();
        Task::truncate();
        Vote::truncate();
        Transaction::truncate();
        Message::truncate();
        Threadable::truncate();
        Thread::truncate();
        DB::statement("SET foreign_key_checks=1");

        $bar->finish();
    }
}
