<?php

namespace App\Console\Commands\Ones;

use App\Models\Game;
use App\Models\Rating\ChannelHistory;
use App\Models\Rating\Channel;
use App\Models\Rating\GameChannelHistory;
use App\Models\Rating\GameHistory;
use App\Models\User;
use App\Notifications\NotifyFollowersAboutStream;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use NewTwitchApi\HelixGuzzleClient;
use NewTwitchApi\NewTwitchApi;

class RecalculateWeekRating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:recalculate_top';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate rating & new top.';
    protected $games = [];
    protected $token = "";
    protected $friday = "";
    protected $prevDay = "";
    protected $nextDay = "";

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
        $this->GetTwitchToken();

        $prevFriday = date('d.m.Y', strtotime('previous Friday', strtotime(Carbon::now('UTC'))));
        $prevFriday = Carbon::parse($prevFriday);

        for($weeks = 1; $weeks>=1; $weeks--)    //recalculate for last 3 weeks
        {
            if($weeks==1)
                $this->friday = $prevFriday;
            else
                $this->friday = Carbon::parse($prevFriday)->subDays(($weeks-1) * 7);

            $this->prevDay = Carbon::parse($this->friday)->subDays(2);
            $this->nextDay =  Carbon::parse($this->friday)->addDays(2);

            echo $this->friday."\r\n";

            $this->NotifyAdmin([
                'friday' => $this->friday,
                'title' => 'Recalculate of rating started'
            ]);

            /*$this->CalculateChannelsRating();
            $this->UpdateChannelsTop();
            $this->CalculateGameRating();
            $this->HistorySetChannelPlace();
            $this->HistorySetGamePlace();*/

            $this->UpdateChannelsInfo();

            $this->NotifyAdmin([
                'friday' => $this->friday,
                'title' => 'Recalculate of rating finished'
            ]);
        }

        $bar->finish();
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function GetTwitchToken()
    {
        $clientId = config('app.rating_twitch_api_key');
        $clientSecret = config('app.rating_twitch_api_secret');
        $helixGuzzleClient = new HelixGuzzleClient($clientId);
        $newTwitchApi = new NewTwitchApi($helixGuzzleClient, $clientId, $clientSecret);

        //Get Token
        $response = $newTwitchApi->getOauthApi()->getAppAccessToken();
        $content = json_decode($response->getBody()->getContents());

        $this->token = $content->access_token;
    }

    /**
     * @param $ids
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function GetChannelsInfoByIds($ids)
    {
        $clientId = config('app.rating_twitch_api_key');
        $clientSecret = config('app.rating_twitch_api_secret');
        $helixGuzzleClient = new HelixGuzzleClient($clientId);
        $newTwitchApi = new NewTwitchApi($helixGuzzleClient, $clientId, $clientSecret);

        $response = $newTwitchApi->getUsersApi()->getUsers($ids, [], false, $this->token);
        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param $exid
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function GetChannelFollowers($exid)
    {
        $clientId = config('app.rating_twitch_api_key');
        $clientSecret = config('app.rating_twitch_api_secret');
        $helixGuzzleClient = new HelixGuzzleClient($clientId);
        $newTwitchApi = new NewTwitchApi($helixGuzzleClient, $clientId, $clientSecret);

        $response = $newTwitchApi->getUsersApi()->getUsersFollows(null, $exid, null, null, $this->token);
        return json_decode($response->getBody()->getContents());
    }

    public function UpdateChannelsInfo()
    {
        Channel::top()->chunk(100, function($channels) {
            $ids = [];
            $chs = [];
            foreach ($channels as $stat) {
                $ids[] = $stat->exid;
                $chs[$stat->exid] = $stat;

                try {
                    if (isset($stat->lastHistory[0]) && $stat->lastHistory[0]->followers==0) {

                        $content = $this->GetChannelFollowers($stat->exid);
                        $stat->lastHistory[0]->update([
                            'followers' => ceil($content->total*0.97)
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::info('UpdateChannelsInfo (Followers) in CalculateTop', [
                        'error' => $e->getMessage(),
                        'file' => __FILE__,
                        'line' => __LINE__
                    ]);
                }
            }

            try {
                $content = $this->GetChannelsInfoByIds($ids);

                if (count($content->data) > 0)
                {
                    foreach ($content->data as $data)
                    {
                        $channel = $chs[$data->id];

                        if (isset($channel->lastHistory[0]) && $channel->lastHistory[0]->views==0)
                        {
                            $channel->lastHistory[0]->update([
                                'views' => ceil($data->view_count*0.97)
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::info('UpdateChannelsInfo (Views) in CalculateTop', [
                    'error' => $e->getMessage(),
                    'file' => __FILE__,
                    'line' => __LINE__
                ]);
            }
            sleep(1);
        });
    }

    public function CalculateChannelsRating()
    {
        foreach(Channel::all() as $channel)
        {
            $streams = is_array($channel->streams) ? $channel->streams : [];
            $rating = 0;

            if(count($streams)>0)
            {
                foreach($streams as $stream)
                {
                    if(abs(Carbon::parse($this->friday)->startOfDay()->diffInDays($stream['started_at'], false))>7) continue;
                    if(Carbon::parse($this->friday)->lt($stream['started_at'])) continue;

                    $rating += $stream['views'];
                }
            }

            $channel->update([
                'rating' => $rating
            ]);

            ChannelHistory::where('channel_id', $channel->id)
                            ->where('created_at', '>', $this->prevDay)
                            ->where('created_at', '<', $this->nextDay)
                            ->update(['rating' => $rating]);
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

    public function calculateGameRating()
    {
        $channels = Channel::top()->get();

        GameHistory::where('created_at', '>', $this->prevDay)
                    ->where('created_at', '<', $this->nextDay)
                    ->update(['time' => 0]);

        GameChannelHistory::where('created_at', '>', $this->prevDay)
                            ->where('created_at', '<', $this->nextDay)
                            ->update(['time' => 0]);

        foreach($channels as $channel)
        {
            $streams = is_array($channel->streams) ? $channel->streams : [];

            if(count($streams)>0)
            {
                foreach($streams as $stream)
                {
                    if(abs(Carbon::parse($this->friday)->startOfDay()->diffInDays($stream['started_at'], false))>7) continue;
                    if(Carbon::parse($this->friday)->lt($stream['started_at'])) continue;

                    if(isset($stream['game_id']) && isset($this->games[$stream['game_id']]))
                    {
                        $game_id = $this->games[$stream['game_id']];
                        echo $game_id."\r\n";

                        $rating = $stream['views'];
                        $gamesHistory = GameHistory::where('game_id', $game_id)
                                                    ->where('created_at', '>', $this->prevDay)
                                                    ->where('created_at', '<', $this->nextDay);

                        if($gamesHistory->count()>0)
                        {
                            $gh = $gamesHistory->first();
                            $gh->update(['time' => $gh->time + $rating ]);
                        }

                        $gamesChannelsHistory = GameChannelHistory::where('created_at', '>', $this->prevDay)
                            ->where('created_at', '<', $this->nextDay)
                            ->where('game_history_id', $gh->id)
                            ->where('channel_id', $channel->id);

                        if($gamesChannelsHistory->count()>0)
                        {
                            $gch = $gamesChannelsHistory->first();
                            $gch->update(['time' => $gch->time + $rating ]);
                        }
                    }
                }
            }
        }

        $gamesHistory = GameHistory::where('created_at', '>', $this->prevDay)
                                    ->where('created_at', '<', $this->nextDay)
                                    ->get();

        if(count($gamesHistory)>0)
        {
            foreach($gamesHistory as $gh)
            {
                $gh->game->update(['rating' => $gh->time]);
            }
        }
    }

    public function HistorySetChannelPlace()
    {
        $history = ChannelHistory::where('created_at', '>', $this->prevDay)
                                    ->where('created_at', '<', $this->nextDay)
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
        $history = GameHistory::where('created_at', '>', $this->prevDay)
                                ->where('created_at', '<', $this->nextDay)
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
            $history = GameChannelHistory::where('created_at', '>', $this->prevDay)
                ->where('created_at', '<', $this->nextDay)
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

    /**
     * @param $channel
     */
    public function NotifyAdmin($data)
    {
        Log::info('Recalculate '.$data['title']." ".$data['friday']->toDateString(), [
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        $user = User::where('email', 'hawkart@rambler.ru')->first();

        $details = [
            'greeting' => 'Здравствуйте. '.$user->name,
            'body' => $data['title'],
            'actionText' => 'Перейти',
            'actionURL' => "https://darestreams.com",
            'subject' => $data['title']." ".$data['friday']->toDateString()
        ];

        $user->notify(new NotifyFollowersAboutStream($details));
    }

}