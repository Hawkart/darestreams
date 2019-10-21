<?php

namespace App\Console\Commands\Ones;

use App\Acme\Helpers\TwitchHelper;
use Illuminate\Console\Command;

class GetChannelVideosOnTwitch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channel:twitch_videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get channel's videos from twitch.";

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

        $twitch = new TwitchHelper();
        $data = $twitch->getChannelVideos(125387632, 5, 0, 'archive');

        if(!empty($data) && isset($data['videos']) && $data['_total']>0)
        {
            dd($data);
        }

        $bar->finish();
    }
}