<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TransferChannelLogoToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update_logo_from_channel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users logo from channel';

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

        $users = User::with(['channel'])->get();
        if(count($users)>0)
        {
            foreach($users as $user)
            {
                if($user->channel)
                    $user->update(['avatar' => $user->channel->logo]);
            }
        }

        $bar->finish();
    }
}
