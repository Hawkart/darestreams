<?php

namespace App\Console\Commands;

use App\Models\Rating\ChannelHistory;
use App\Models\Streamer;
use App\Models\Rating\Channel;
use App\Acme\Helpers\TwitchHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateRatingTop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:calculate_top';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get views for active streams.';

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

        if(Channel::count()==0)
            $this->importFromStreamers();


        $bar->finish();
    }

    private function importFromStreamers()
    {
        Streamer::chunk(200, function ($streamers)
        {
            foreach($streamers as $streamer)
            {
                $channel = Channel::create([
                    'provider' => 'twitch',
                    'name' => $streamer->name,
                    'exid' => $streamer->json['_id'],
                    'json' => $streamer->json
                ]);

                $data = []; //$this->countRatingByVideos($channel->exid);

                ChannelHistory::create([
                    'channel_id' => $channel->id,
                    'followers' => !empty($data) ? $data['followers'] : $channel->json['channel']['followers'],
                    'views' => !empty($data) ? $data['views'] : $channel->json['channel']['views'],
                    'rating' => !empty($data) ? $data['rating'] : 0
                ]);

                sleep(1);
            }
        });
    }

    /**
     * Get rating by videos, followers and views for channel
     * @param $channel_id
     */
    private function countRatingByVideos($channel_id)
    {
        $twitch = new TwitchHelper();
        $data = $twitch->getChannelVideos($channel_id, 50, 0, 'archive');

        if(isset($data['videos']) && count($data['_total'])>0)
        {
            $rating = 0;

            foreach($data['videos'] as $video)
            {
                $start_at = $video['published_at'];
                $diff = Carbon::now('UTC')->startOfDay()->diffInDays($start_at, false);

                if($diff>7) break;

                $rating+= ceil($video['views']*$video['length']/3600);
            }

            return [
                'followers' => $data['channel']['followers'],
                'views' => $data['channel']['views'],
                'rating' => $rating
            ];
        }

        return [];
    }

    private function getTopByFollowers()
    {
        return Channel::select("*",
            \DB::raw('(SELECT followers FROM stat_channel_history as h WHERE h.channel_id = stat_channels.id  ORDER BY id DESC LIMIT 1) as followers'))
            ->orderBy('followers', 'DESC')
            ->limit(250)
            ->get();
    }

    private function getTopByViews()
    {
        return Channel::select("*",
            \DB::raw('(SELECT views FROM stat_channel_history as h WHERE h.channel_id = stat_channels.id  ORDER BY id DESC LIMIT 1) as views'))
            ->orderBy('views', 'DESC')
            ->limit(250)
            ->get();
    }

    private function getOnly500()
    {
        $channel = \App\Models\Channel::all();
    }
}