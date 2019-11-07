<?php

namespace App\Console\Commands\Ones;

use App\Acme\Helpers\TwitchHelper;
use App\Enums\StreamStatus;
use App\Models\Channel;
use Illuminate\Console\Command;

class GetLinksVideosFromTwitch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channel:twitch_videos_links';

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

        $channels = Channel::whereHas('user', function($q){
               $q->where('role_id', '<>', 1);
            })
            ->whereHas('streams', function($q){
                $q->whereIn('status', [StreamStatus::FinishedWaitPay, StreamStatus::FinishedIsPayed]);
            }, '>', 0)
            ->with('streams')
            ->get();

        if(count($channels)>0)
        {
            foreach($channels as $channel)
            {
                $twitch = new TwitchHelper();
                $data = $twitch->getChannelVideos($channel->exid, 25, 0, 'archive');

                if(!empty($data) && isset($data['videos']) && $data['_total']>0)
                {
                    foreach($data['videos'] as $video)
                    {
                        dd($video);
                    }
                }
            }
        }



        $bar->finish();
    }
}