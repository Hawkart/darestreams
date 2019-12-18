<?php

namespace App\Console\Commands\Ones;

use App\Models\Game;
use App\Models\Rating\Channel;
use App\Models\Rating\ChannelHistory;
use App\Models\Rating\GameChannelHistory;
use App\Models\Rating\GameHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class BugfixRatingGameChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bugfix:rating-game-channel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';
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
        /*$prevDay = Carbon::now('UTC')->subDays(5);
        $prevWeek = Carbon::now('UTC')->subDays(10);

        GameChannelHistory::where('created_at', '>', $prevDay)->delete();
        ChannelHistory::where('created_at', '>', $prevDay)->delete();

        $histories = ChannelHistory::where('created_at', '>', $prevWeek)->with(['channel'])->get();
        foreach($histories as $history)
        {
            $history->channel->update([
                'followers' => $history->followers,
                'views' => $history->views,
                'rating' => $history->rating
            ]);
        }*/

        /*DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        GameChannelHistory::truncate();
        GameHistory::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();*/

        $this->getGamesList();
        $this->clearRating();
        $this->calculateGameRating();
        $this->historySetGamePlace();

        $bar->finish();
    }

    public function clearRating()
    {
        $prevDay = Carbon::now('UTC')->subDays(12);
        $ghs = GameHistory::where('created_at', '>', $prevDay)->get();
        foreach($ghs as $gh)
        {
            $gh->update(['time' => 0]);
        }

        $ghs = GameChannelHistory::where('created_at', '>', $prevDay)->get();
        foreach($ghs as $gh)
        {
            $gh->update(['time' => 0]);
        }
    }

    public function calculateGameRating()
    {
        $prevDay = Carbon::now('UTC')->subDays(12);
        $channels = Channel::top()->get();

        foreach($channels as $channel)
        {
            $streams = is_array($channel->streams) ? $channel->streams : [];

            if(count($streams)>0)
            {
                foreach($streams as $stream)
                {
                    if(abs(Carbon::now('UTC')->startOfDay()->diffInDays($stream['started_at'], false))>12) continue;

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

    public function historySetGamePlace()
    {
        $prevDay = Carbon::now('UTC')->subDays(2);

        $history = GameHistory::where('updated_at', '>', $prevDay)
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
                ->where('game_id', $game_id)
                ->orderBy('time', 'DESC')
                ->get();

            $place = 1;
            foreach($history as $h)
            {
                $h->update(['place' => $place]);
                $place++;
            }
        }

        echo "places updated";
    }

    public function getGamesList()
    {
        $this->games = Game::pluck('id', 'twitch_id')->toArray();
    }
}