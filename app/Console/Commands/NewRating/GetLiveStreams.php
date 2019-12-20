<?php

namespace App\Console\Commands\NewRating;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Models\Game;
use App\Models\Stream;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use NewTwitchApi\HelixGuzzleClient;
use NewTwitchApi\NewTwitchApi;
use App\Models\Rating\Channel as StatChannel;
use App\Models\Channel;
use App\Notifications\NotifyFollowersAboutStream;
use Illuminate\Support\Facades\Log;

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
    public $games = [];
    public $token = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->token = $this->GetTwitchToken();
    }

    public function handle()
    {
        $bar = $this->output->createProgressBar(100);

        $this->GetGamesList();
        $this->CheckChannelsStreams();
        $this->UpdateStatChannels();

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

        return $content->access_token;
    }

    /**
     * @param $ids
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function GetStreamsByIds($ids)
    {
        $clientId = config('app.rating_twitch_api_key');
        $clientSecret = config('app.rating_twitch_api_secret');
        $helixGuzzleClient = new HelixGuzzleClient($clientId);
        $newTwitchApi = new NewTwitchApi($helixGuzzleClient, $clientId, $clientSecret);

        $response = $newTwitchApi->getStreamsApi()->getStreams($ids, [], [], [], [], null, null, null, $this->token);
        $streams = json_decode($response->getBody()->getContents());

        return $streams;
    }

    public function CheckChannelsStreams()
    {
        $date = Carbon::now('UTC')->subMinutes(10);
        $statuses = [StreamStatus::FinishedIsPayed, StreamStatus::FinishedWaitPay];

        Channel::chunk(100, function($channels) use ($date, $statuses)
        {
            $ids = [];
            $chs = [];
            foreach ($channels as $stat)
            {
                $ids[] = $stat->exid;
                $chs[$stat->exid] = $stat;
            }

            //1. Active in Dare but not active in Twitch (started_at)
            //2. Finish in Dare but not in Twitch (ended at)
            //3. Stream in Twitch but not created/active in Dare.

            try {
                $content = $this->GetStreamsByIds($ids);

                if(count($content->data)>0)
                {
                    foreach($content->data as $stream)
                    {
                        if($stream->type=='live')
                        {
                            $channel = $chs[$stream->user_id];
                            unset($chs[$stream->user_id]);

                            //2. Finish in Dare but not in Twitch (ended at)
                            if(Stream::where('channel_id', $channel->id)->whereIn('status', $statuses)
                                    ->where('ended_at', '>', $date)->count()>0)
                            {
                                $this->NotifyToFinishInTwitch($channel, $stream);
                            }else{

                                $activeStream = Stream::where('channel_id', $channel->id)
                                    ->where('status', StreamStatus::Active)
                                    ->first();

                                if($activeStream) {
                                    $this->UpdateViewsAndInfoOfStream($activeStream, $stream);
                                } else {

                                    //3. Stream in Twitch started but not in Dare.
                                    $now = Carbon::now('UTC');
                                    $start_at = Carbon::parse($stream->started_at);
                                    $minutes = ceil($now->diffInSeconds($start_at)/60);
                                    if($minutes<10)
                                    {
                                        //Check exist created stream
                                        $createdStream = Stream::where('channel_id', $channel->id)
                                            ->where('status', StreamStatus::Created)
                                            ->first();

                                        //create new stream by system
                                        if(!$createdStream)
                                        {
                                            $this->CreateForTestNewStream($channel, $stream);
                                        }

                                        //$this->NotifyCreateStreamInDareStreams($channel);
                                    }
                                }
                            }
                        }
                    }
                }

            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            if(count($chs)>0)
            {
                foreach($chs as $channel)
                {
                    $streams = Stream::where('channel_id', $channel->id)->where('status', StreamStatus::Active)
                        ->where('start_at', '>', $date);

                    //1. Active in Dare but not active in Twitch (started_at)
                    if($streams->count()>0)
                    {
                        $stream = $streams->first();

                        //if created by system that means stream in Twitch just finished
                        if($stream->created_by_system)
                        {
                            $this->FinishTestStream($stream);
                        }else{
                            $this->NotifyCreateStreamInTwitch($channel);
                        }
                    }
                }
            }
        });
    }

    public function UpdateViewsAndInfoOfStream($activeStream, $stream)
    {
        $activeStream->update([
            'views' =>  $stream->viewer_count,
            'preview' =>  str_replace(['{width}', '{height}'], [1200, 800], $stream->thumbnail_url)
        ]);
    }

    public function UpdateStatChannels()
    {
        StatChannel::chunk(100, function ($channels){
            $ids = [];
            $chs = [];
            foreach ($channels as $stat) {
                $ids[] = $stat->exid;
                $chs[$stat->exid] = $stat;
            }

            try {
                $content = $this->GetStreamsByIds($ids);

                if (count($content->data) > 0) {
                    foreach ($content->data as $stream) {
                        if ($stream->type == 'live') {
                            $channel = $chs[$stream->user_id];

                            $this->AdminNotifyAboutNewStream($channel, $stream);
                            $this->UpdateChannelStreams($channel, $stream);
                        }
                    }
                }

            } catch (\Exception $e) {

                Log::info('UpdateStatChannels in GetLiveStreams', [
                    'error' => $e->getMessage(),
                    'file' => __FILE__,
                    'line' => __LINE__
                ]);
            }
        });
    }

    /**
     * @param $channel
     * @param $stream
     */
    public function UpdateChannelStreams($channel, $stream)
    {
        $frequencyUpdate = 5;   //every 5 minutes
        $exist = false;
        $streams = is_array($channel->streams) ? $channel->streams : [];
        $views = ceil($stream->viewer_count*$frequencyUpdate/60);
        $length = $frequencyUpdate*60;

        if(is_array($streams) && count($streams)>0)
        {
            foreach($streams as &$s)
            {
                if($s['id'] == $stream->id)
                {
                    $s['views']+= $views;

                    if(isset($s['length']))
                        $s['length']+=$length*60;
                    else
                        $s['length'] = $length*60;

                    $exist = true;
                }
            }
        }

        if(!$exist)
        {
            $streams[] = [
                'id' => $stream->id,
                'views' => $views,
                'started_at' => $stream->started_at,
                'game_id' => $stream->game_id,
                'length' => $length
            ];
        }

        $channel->update(['streams' => $streams]);
    }

    /**
     * @param $channel
     * @param $stream
     */
    public function CreateForTestNewStream($channel, $stream)
    {
        $now = Carbon::now('UTC');
        Stream::create([
            "title" => isset($stream->title) ? $stream->title : $channel->name,
            'views' =>  $stream->viewer_count,
            'preview' =>  str_replace(['{width}', '{height}'], [1200, 800], $stream->thumbnail_url),
            "game_id" => isset($this->games[$stream['game_id']]) ? $this->games[$stream['game_id']] : $channel->game_id,
            "link" => 'https://twitch.tv/'.$channel->name,
            "channel_id" => $channel->id,
            "started_at" => $now,
            "status" => StreamStatus::Active,
            "allow_task_before_stream" => 1,
            "allow_task_when_stream" => 1,
            "min_amount_task_before_stream" => 50,
            "min_amount_task_when_stream" => 50,
            "min_amount_donate_task_before_stream" => 50,
            "min_amount_donate_task_when_stream" => 50,
            "created_by_system" => true
        ]);
    }

    /**
     * @param $stream
     */
    public function FinishTestStream($stream)
    {
        $stream->update([
            "status" => StreamStatus::FinishedWaitPay,
            "ended_at" => Carbon::now('UTC')
        ]);

        $tasks = $stream->tasks;
        if(count($tasks)>0)
        {
            foreach($tasks as $task)
            {
                if($task->amount_donations==0 && $task->adv_task_id==0)
                {
                    $task->update(['status' => TaskStatus::PayFinished]);
                }else{
                    $task->update(['status' => TaskStatus::AllowVote]);
                }
            }
        }
    }

    /**
     * @param $channel
     * @param $stream
     */
    public function AdminNotifyAboutNewStream($channel, $stream)
    {
        $admin = User::admins()->where('email', config('mail.admin_email'))->first();

        $now = Carbon::now('UTC');
        $start_at = Carbon::parse($stream->started_at);
        $minutes = ceil($now->diffInSeconds($start_at)/60);

        if($minutes<10)
        {
            $subject = 'New stream on Twitch of '.$channel->name;

            if(intval($channel->channel_id)>0)
                $subject= "Real ".$subject;

            $details = [
                'greeting' => 'Hi '.$admin->name,
                'body' => 'The stream of '.$channel->name." started",
                'actionText' => 'View stream',
                'actionURL' => 'https://twitch.tv/'.$channel->name,
                'subject' => $subject
            ];

            $admin->notify(new NotifyFollowersAboutStream($details));
        }
    }

    /**
     * @param $channel
     */
    public function NotifyToFinishInTwitch($channel)
    {
        $user = $channel->user;

        $details = [
            'greeting' => 'Здравствуйте. '.$user->name,
            'body' => "Не забудьте завершить стрим на Твиче",
            'actionText' => 'Перейти',
            'actionURL' => 'https://twitch.tv/'.$user->nickname,
            'subject' => "Завершите стрим на Твиче"
        ];

        $user->notify(new NotifyFollowersAboutStream($details));
    }

    /**
     * @param $channel
     */
    public function NotifyCreateStreamInDareStreams($channel)
    {
        $user = $channel->user;

        $details = [
            'greeting' => 'Здравствуйте. '.$user->name,
            'body' => "Создайте трансляцию на DareStreams, чтобы получить еще больше донатов.",
            'actionText' => 'Перейти',
            'actionURL' => 'https://darestreams.com',
            'subject' => "Создайте трансляцию - получите донаты"
        ];

        $user->notify(new NotifyFollowersAboutStream($details));
    }

    /**
     * @param $channel
     */
    public function NotifyCreateStreamInTwitch($channel)
    {
        $user = $channel->user;

        $details = [
            'greeting' => 'Здравствуйте. '.$user->name,
            'body' => "Трансляция на DareStreams уже началась. Создайте, пожалуйста, стрим на Twitch!",
            'actionText' => 'Перейти',
            'actionURL' => 'https://twitch.tv/'.$user->nickname,
            'subject' => "Создайте стрим на Twitch"
        ];

        $user->notify(new NotifyFollowersAboutStream($details));
    }

    /**
     * Game list
     */
    public function GetGamesList()
    {
        $this->games = Game::pluck('id', 'twitch_id')->toArray();
    }
}