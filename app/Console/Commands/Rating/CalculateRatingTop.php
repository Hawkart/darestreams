<?php

namespace App\Console\Commands\Rating;

use App\Models\Game;
use App\Models\Rating\ChannelHistory;
use App\Models\Rating\Channel;
use App\Models\Channel as Ch;
use App\Acme\Helpers\TwitchHelper;
use App\Models\Rating\GameChannelHistory;
use App\Models\Rating\GameHistory;
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
    protected $games = [];

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

        $this->getGamesList();
        $this->updateTop();
        $this->calculateChannelRating();
        $this->calculateGameRating();
        $this->historySetPlace();

        $bar->finish();
    }

    public function calculateGameRating()
    {
        $prevDay = Carbon::now('UTC')->subDays(2);
        $channels = Channel::top()->get();

        $ratings = [];
        foreach($channels as $channel)
        {
            $streams = is_array($channel->streams) ? $channel->streams : [];

            if(count($streams)>0)
            {
                foreach($streams as $stream)
                {
                    if(abs(Carbon::now('UTC')->startOfDay()->diffInDays($stream['started_at'], false))>7) continue;

                    if(isset($stream['length']) && isset($stream['game_id']) && intval($stream['length'])>0 && isset($this->games[$stream['game_id']]))
                    {
                        $game_id = $this->games[$stream['game_id']];
                        echo $game_id."\r\n";

                        $rating = ceil($stream['views']*$stream['length']/3600);
                        $gamesHistory = GameHistory::where('created_at', '>', $prevDay)->where('game_id', $game_id);

                        if($gamesHistory->count()>0)
                        {
                            $gh = $gamesHistory->first();
                            $gh->update(['time' => $gh->time + $rating ]);
                        }else{
                            $gh = GameHistory::create([
                                'game_id' =>  $game_id,
                                'time' => $rating
                            ]);
                        }

                        $gamesChannelsHistory = GameChannelHistory::where('created_at', '>', $prevDay)
                            ->where('game_history_id', $gh->id)
                            ->where('channel_id', $channel->id);

                        if($gamesChannelsHistory->count()>0)
                        {
                            $gch = $gamesChannelsHistory->first();
                            $gch->update(['time' => $gh->time + $rating ]);
                        }else{
                            GameChannelHistory::create([
                                'game_history_id' =>  $gh->id,
                                'channel_id' => $channel->id,
                                'time' => ceil($stream['views']*$stream['length']/3600)
                            ]);
                        }

                        if(isset($ratings[$game_id]))
                            $ratings[$game_id]+= $rating;
                        else
                            $ratings[$game_id] = $rating;
                    }
                }
            }
        }

        if(count($ratings)>0)
        {
            foreach(Game::all() as $game)
            {
                if(isset($ratings[$game->id]))
                {
                    $game->update(['rating' => $ratings[$game->id]]);
                }else{
                    $game->update(['rating' => 0]);

                    GameHistory::create([
                        'game_id' =>  $game->id,
                        'time' => 0
                    ]);
                }
            }
        }
    }

    public function calculateChannelRating()
    {
        $prevDay = Carbon::now('UTC')->subDays(2);
        $channels = Channel::top()->whereHas('history', function($q) use ($prevDay){
            $q->where('updated_at', '>', $prevDay);
        }, '=', 0)->limit(50)->get();

        foreach($channels as $channel)
        {
            $data = $this->countRatingByVideos($channel);

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
    protected function countRatingByVideos($channel)
    {
        $twitch = new TwitchHelper('r');
        $data = $twitch->getChannelVideos($channel->exid, 50, 0, 'archive');

        $streams = $channel->streams;

        if(isset($data['videos']) && intval($data['_total'])>0)
        {
            $rating = 0;

            foreach($data['videos'] as $video)
            {
                $start_at = $video['published_at'];
                $diff = Carbon::now('UTC')->startOfDay()->diffInDays($start_at, false);

                if(abs($diff)>7) break;

                try{
                    if(count($streams)>0)
                    {
                        foreach($streams as &$stream)
                        {
                            if($stream['started_at']==$video['created_at'] || $stream['id']==$video['broadcast_id'])
                            {
                                $rating+= ceil($stream['views']*$video['length']/3600);
                                $stream['length'] = $video['length'];
                                break;
                            }
                        }
                    }
                } catch(\Exception $e){
                    $rating+= ceil($video['views']*$video['length']/3600);
                }
            }

            return [
                'followers' => $data['videos'][0]['channel']['followers'],
                'views' => $data['videos'][0]['channel']['views'],
                'rating' => $rating,
                'streams' => $streams
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
        $adminChannels = Ch::whereHas('user', function($q){
            $q->where("role_id", '<>', 1);
        })->pluck('exid')->toArray();

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

        $top = array_diff($top, $adminChannels);

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
            /*$ids = $this->getAllTop();

            echo "count top = ".count($ids)."\r\n";

            Channel::top()->update(['top' => 0]);
            Channel::whereIn('exid', $ids)->update(['top' => 1]);*/

            Channel::top()->update(['top' => 1]);
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

    public function getGamesList()
    {
        $this->games = Game::pluck('id', 'twitch_id')->toArray();
    }
}