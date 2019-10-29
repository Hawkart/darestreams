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

class DeleteUserAndData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete {--user_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete user and fake data.';

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

        $user_id = $this->option('user_id');

        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $users = [];
        if(!empty($user_id))
        {
            $users[] = User::findOrFail($user_id);
        }else{
            $users = User::where('fake', 1)->get();
        }

        if(count($users)>0)
        {
            foreach($users as $user)
            {
                $user->clearFakeData();
                $user->oauthProviders->delete();
                $user->account->delete();
                $user->channel->delete();
                $user->delete();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();

        $bar->finish();
    }
}
