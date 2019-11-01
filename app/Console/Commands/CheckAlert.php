<?php

namespace App\Console\Commands;

use App\Acme\Helpers\Streamlabs\StreamlabsApi;
use App\Models\User;
use Illuminate\Console\Command;

class CheckAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streamlabs:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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

        $streamer = User::findOrFail(107);

        $streamlabs = $streamer->oauthProviders()->where('provider', 'streamlabs')->first();
        if($streamlabs)
        {
            $sapi = new StreamlabsApi([]);
            $sapi->alert([
                "message" => "You have 1 new task!",
                "user_message" => "View on DearStreams!",
                "access_token" => $streamlabs->access_token
            ]);
        }

        $bar->finish();
    }
}
