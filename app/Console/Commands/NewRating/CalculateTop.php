<?php

namespace App\Console\Commands\NewRating;

use App\Models\Game;
use App\Models\Rating\ChannelHistory;
use App\Models\Rating\Channel;
use App\Models\Channel as Ch;
use App\Acme\Helpers\TwitchHelper;
use App\Models\Rating\GameChannelHistory;
use App\Models\Rating\GameHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateTop extends Command
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
    protected $description = 'Calculate rating & new top.';
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

        $this->GetGamesList();
        $this->CalculateChannelsRating();
        $this->UpdateChannelsTop();
        $this->UpdateChannelsInfo();

        $this->CalculateGameRating();
        $this->HistorySetChannelPlace();
        $this->HistorySetGamePlace();

        $bar->finish();
    }

    public function CalculateChannelsRating()
    {
        foreach(Channel::all() as $channel)
        {
            echo "channel_id = ".$channel->id."\r\n";

            $streams = $channel->streams;
            $rating = 0;

            if(count($streams)>0)
            {
                foreach($streams as $stream)
                {
                    if(abs(Carbon::now('UTC')->startOfDay()->diffInDays($stream['started_at'], false))>7) continue;

                    if(isset($stream['length']) && intval($stream['length'])>0)
                    {
                        $rating += ceil($stream['views'] * $stream['length'] / 3600);
                    }
                }
            }

            $channel->update([
                'rating' => $rating
            ]);

            ChannelHistory::create([
                'channel_id' => $channel->id,
                'followers' => 0,
                'views' => 0,
                'rating' => $rating
            ]);
        }
    }

    public function UpdateChannelsTop()
    {
        Channel::top()->update(['top' => 0]);
        Channel::where('rating', ">", 0)
            ->orderBy('rating', 'desc')
            ->limit(500)
            ->update(['top' => 1]);
    }

    public function UpdateChannelsInfo()
    {
        //info by channel
        //get followers count
    }

    public function calculateGameRating()
    {
        $prevDay = Carbon::now('UTC')->subDays(2);
        $channels = Channel::top()->get();

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
                            $gch->update(['time' => $gch->time + $rating ]);
                        }else{
                            GameChannelHistory::create([
                                'game_history_id' =>  $gh->id,
                                'channel_id' => $channel->id,
                                'time' => $rating
                            ]);
                        }
                    }
                }
            }
        }

        $gamesHistory = GameHistory::where('created_at', '>', $prevDay)->with(['game'])->get();
        foreach($gamesHistory as $gh)
        {
            $gh->game->update(['rating' => $gh->time]);
        }
    }


    public function HistorySetChannelPlace()
    {
        $prevDay = Carbon::now('UTC')->subDays(2);

        $history = ChannelHistory::where('created_at', '>', $prevDay)
            ->orderBy('rating', 'DESC')
            ->get();

        $place = 1;
        foreach($history as $h)
        {
            $h->update(['place' => $place]);
            $place++;
        }

        echo "places updated"."\r\n";
    }

    public function HistorySetGamePlace()
    {
        $prevDay = Carbon::now('UTC')->subDays(2);

        $history = GameHistory::where('created_at', '>', $prevDay)
            ->where('place', 0)
            ->orderBy('time', 'DESC')
            ->get();

        $place = 1;
        foreach($history as $h)
        {
            $h->update(['place' => $place]);
            $place++;
        }

        foreach($this->games as $game_id)
        {
            //GameChannelHistory
            $history = GameChannelHistory::where('updated_at', '>', $prevDay)
                ->where('place', 0)
                ->whereHas('gameHistory', function($q) use ($game_id){
                    $q->where('game_id', $game_id);
                })
                ->orderBy('time', 'DESC')
                ->get();

            $place = 1;
            foreach($history as $h)
            {
                $h->update(['place' => $place]);
                $place++;
            }
        }

        echo "places updated"."\r\n";
    }

    public function GetGamesList()
    {
        $this->games = Game::pluck('id', 'twitch_id')->toArray();
    }
}