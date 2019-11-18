<?php

namespace App\Console\Commands\Ones;

use Illuminate\Console\Command;
use NewTwitchApi\HelixGuzzleClient;
use NewTwitchApi\NewTwitchApi;
use App\Models\Rating\Channel as StatChannel;
use App\Models\Channel;

class GetLiveStreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitch:get_live_streams';

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

        $clientId = config('app.rating_twitch_api_key');
        $clientSecret = config('app.rating_twitch_api_secret');
        $helixGuzzleClient = new HelixGuzzleClient($clientId);
        $newTwitchApi = new NewTwitchApi($helixGuzzleClient, $clientId, $clientSecret);

        StatChannel::top()->chunk(100, function($channels) use ($newTwitchApi)
        {
            $ids = [];
            $chs = [];
            foreach ($channels as $stat)
            {
                $ids[] = $stat->exid;
                $chs[$stat->exid] = $stat;
            }

            try {
                $response = $newTwitchApi->getStreamsApi()->getStreams($ids);
                $content = json_decode($response->getBody()->getContents());

                if(count($content->data)>0)
                {
                    foreach($content->data as $stream)
                    {
                        if($stream->type=='live')
                        {
                            $channel = $chs[$stream->user_id];
                            $this->UpdateChannelStreams($channel, $stream);
                        }
                    }
                }

            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            sleep(1);
        });

        $bar->finish();
    }

    public function UpdateChannelStreams($channel, $stream)
    {
        $exist = false;
        $streams = $channel->streams;
        foreach($streams as &$s)
        {
            if($s['id'] == $stream->id)
            {
                $s['views']+= ceil($stream->viewer_count/6);
                $exist = true;
            }
        }

        if(!$exist)
        {
            $streams = [
                [
                    'id' => $stream->id,
                    'views' => ceil($stream->viewer_count/6),
                    'started_at' => $stream->started_at
                ]
            ];
        }

        $channel->update(['streams' => $streams]);
    }
}