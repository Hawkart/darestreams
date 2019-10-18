<?php

namespace App\Console\Commands;

use App\Models\Rating\ChannelHistory;
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

        $this->updateTop();
        $this->calculateRating();
        $this->historySetPlace();

        $bar->finish();
    }

    public function calculateRating()
    {
        $prevDay = Carbon::now('UTC')->subDays(2);
        $channels = Channel::top()->whereHas('history', function($q) use ($prevDay){
            $q->where('updated_at', '>', $prevDay);
        }, '=', 0)->limit(50)->get();

        foreach($channels as $channel)
        {
            $data = $this->countRatingByVideos($channel->exid);

            echo "channel_id = ".$channel->id."\r\n";

            if(!empty($data))
            {
                echo "followers = ".$data['followers']."\r\n";

                $channel->update([
                    'followers' => $data['followers'],
                    'views' => $data['views'],
                    'rating' => $data['rating']
                ]);

                ChannelHistory::create([
                    'channel_id' => $channel->id,
                    'followers' => $data['followers'],
                    'views' => $data['views'],
                    'rating' => $data['rating']
                ]);

                if($data['rating']==0)
                    $channel->update(['rating' => 0]);
            }else{
                $channel->update(['rating' => 0, 'top' => 0]);

                ChannelHistory::create([
                    'channel_id' => $channel->id,
                    'followers' => $channel['followers'],
                    'views' => $channel['views'],
                    'rating' => 0
                ]);
            }

            sleep(2);
        }
    }

    /**
     * Get rating by videos, followers and views for channel
     * @param $channel_id
     */
    protected function countRatingByVideos($channel_id)
    {
        $twitch = new TwitchHelper('r');
        $data = $twitch->getChannelVideos($channel_id, 50, 0, 'archive');

        if(isset($data['videos']) && intval($data['_total'])>0)
        {
            $rating = 0;

            foreach($data['videos'] as $video)
            {
                $start_at = $video['published_at'];
                $diff = Carbon::now('UTC')->startOfDay()->diffInDays($start_at, false);

                if(abs($diff)>7) break;

                $rating+= ceil($video['views']*$video['length']/3600);
            }

            return [
                'followers' => $data['videos'][0]['channel']['followers'],
                'views' => $data['videos'][0]['channel']['views'],
                'rating' => $rating
            ];
        }

        return [];
    }

    public function getTopByFollowers()
    {
        return Channel::orderBy('followers', 'DESC')->limit(500)->pluck('exid')->toArray();
    }

    public function getTopByViews()
    {
        return Channel::orderBy('views', 'DESC')->limit(500)->pluck('exid')->toArray();
    }

    /**
     * @return array
     */
    public function getAllTop()
    {
        $topF = $this->getTopByFollowers();
        $topV = $this->getTopByViews();
        $current = Channel::where('channel_id', '>', 0)->pluck('exid')->toArray();

        $top = array_merge($topF, $topV, $current);
        $top = array_unique($top);

        if(count($top)>500)
        {
            //delete all not unique items according to sorting in different arrays
            $diff = count($top) - 500;

            $topF = array_diff($topF, $current);
            $topV = array_diff($topV, $current);

            while($diff>0)
            {
                $end = end($topF);
                array_pop($topF);
                if(array_search($end, $topV)===false)
                    $diff--;

                if($diff>0)
                {
                    $end = end($topV);
                    array_pop($topV);
                    if(array_search($end, $topF)===false)
                        $diff--;
                }
            }

            $top = array_merge($topF, $topV, $current);
            $top = array_unique($top);
        }

        return $top;
    }

    public function updateTop()
    {
        $prevDay = Carbon::now('UTC')->subDays(2);

        $channels = Channel::top()
            ->whereHas('history', function($q) use ($prevDay){
                $q->where('updated_at', '>', $prevDay);
            }, '>', 0)->count();

        if($channels==0)
        {
            $ids = $this->getAllTop();

            echo "count top = ".count($ids)."\r\n";

            Channel::top()->update(['top' => 0]);
            Channel::whereIn('exid', $ids)->update(['top' => 1]);
        }
    }

    public function historySetPlace()
    {
        $prevDay = Carbon::now('UTC')->subDays(2);

        $channels = Channel::top()
                        ->whereHas('history', function($q) use ($prevDay){
                            $q->where('updated_at', '>', $prevDay);
                        }, '=', 0)->count();

        if($channels==0)
        {
            $history = ChannelHistory::where('updated_at', '>', $prevDay)
                ->orderBy('rating', 'DESC')
                ->get();

            $place = 1;
            foreach($history as $h)
            {
                $h->update(['place' => $place]);
                $place++;
            }

            echo "places updated";
        }else{
            echo "channels not updated = ".$channels;
        }
    }
}