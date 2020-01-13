<?php

namespace App\Console\Commands\NewRating;

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
    public $token = "";

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

        $this->NotifyAdmin([
            'title' => 'Calculate tip of rating started'
        ]);

        $this->GetTwitchToken();
        $this->GetGamesList();
        $this->CalculateChannelsRating();
        $this->UpdateChannelsTop();
        $this->UpdateChannelsInfo();
        $this->CalculateGameRating();
        $this->HistorySetChannelPlace();
        $this->HistorySetGamePlace();

        $this->NotifyAdmin([
            'title' => 'Calculate tip of rating finished'
        ]);

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

    public function CalculateChannelsRating()
    {
        foreach(Channel::all() as $channel)
        {
            echo "channel_id = ".$channel->id."\r\n";

            $streams = is_array($channel->streams) ? $channel->streams : [];
            $rating = 0;

            if(count($streams)>0)
            {
                foreach($streams as $stream)
                {
                    if(abs(Carbon::now('UTC')->startOfDay()->diffInDays($stream['started_at'], false))>7) continue;

                    $rating += $stream['views'];
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
                    $content = $this->GetChannelFollowers($stat->exid);

                    $stat->update([
                        'followers' => $content->total
                    ]);

                    if (isset($stat->lastHistory[0])) {
                        $stat->lastHistory[0]->update([
                            'followers' => $content->total
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

                        $channel->update([
                            'views' => $data->view_count
                        ]);

                        if (isset($channel->lastHistory[0]))
                        {
                            $channel->lastHistory[0]->update([
                                'views' => $data->view_count,
                                'title' => strtolower($data->login)
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
        });
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

                    if(isset($stream['game_id']) && isset($this->games[$stream['game_id']]))
                    {
                        $game_id = $this->games[$stream['game_id']];
                        echo $game_id."\r\n";

                        $rating = $stream['views'];
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

        $gamesHistory = GameHistory::where('created_at', '>', $prevDay)->get();
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
            $history = GameChannelHistory::where('created_at', '>', $prevDay)
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

    /**
     * @param $channel
     */
    public function NotifyAdmin($data)
    {
        Log::info('CalculateTop: '.$data['title']." ".date("H:i:s"), [
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        $user = User::where('email', 'hawkart@rambler.ru')->first();

        $details = [
            'greeting' => 'Здравствуйте. '.$user->name,
            'body' => $data['title'],
            'actionText' => 'Перейти',
            'actionURL' => "https://darestreams.com",
            'subject' => $data['title']." ".date("H:i:s")
        ];

        $user->notify(new NotifyFollowersAboutStream($details));
    }

    public function GetGamesList()
    {
        $this->games = Game::pluck('id', 'twitch_id')->toArray();
    }
}