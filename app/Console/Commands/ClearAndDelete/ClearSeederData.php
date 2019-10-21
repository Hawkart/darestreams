<?php

namespace App\Console\Commands\ClearAndDelete;

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

        if (!$this->confirm('CONFIRM CLEAR ALL DATA IN DB? [y|N]')) {
            exit('Clear data command aborted');
        }

        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        foreach(\DB::select('SHOW TABLES') as $table) {
            $table_array = get_object_vars($table);
            \Schema::truncate($table_array[key($table_array)]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();

        $bar->finish();
    }
}
