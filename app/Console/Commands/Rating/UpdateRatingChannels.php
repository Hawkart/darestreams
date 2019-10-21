<?php

namespace App\Console\Commands\Rating;

use App\Acme\Helpers\TwitchHelper;
use App\Models\Game;
use App\Models\Streamer;
use App\Models\Rating\Channel as RatingChannel;
use App\Models\Channel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateRatingChannels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:channels_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update channels streams';

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

        if(RatingChannel::count()==0)
        {
            $this->importFromStreamers();
            $this->importFromExistChannels();
        }

        $this->updateChannels();

        $bar->finish();
    }

    protected function updateChannels()
    {
        $searchDay = 'Friday';
        $searchDate = new Carbon('UTC');
        $lastFriday = Carbon::createFromTimeStamp(strtotime("last $searchDay", $searchDate->timestamp));

        $channels = RatingChannel::where('updated_at', '<', $lastFriday)
                        ->inRandomOrder()
                        ->limit(25)
                        ->get();

        if(count($channels)>0)
        {
            foreach($channels as $channel)
            {
                $twitch = new TwitchHelper('r');
                if($data = $twitch->getChannel($channel->exid) && isset($data['_id']))
                {
                    $channel->update([
                        'provider' => 'twitch',
                        'name' => $data['name'],
                        'url' => $data['url'],
                        'exid' => $data['_id'],
                        'json' => $data,
                        'followers' => $data['followers'],
                        'views' => $data['views']
                    ]);
                }

                sleep(1);
            }
        }
    }

    protected function importFromStreamers()
    {
        $ids = [];

        Streamer::chunk(500, function ($streamers) use ($ids)
        {
            foreach($streamers as $streamer)
            {
                $exid = $streamer->json['channel']['_id'];

                $data = [
                    'provider' => 'twitch',
                    'name' => $streamer->name,
                    'url' => $streamer->json['channel']['url'],
                    'exid' => $exid,
                    'json' => $streamer->json['channel'],
                    'followers' => $streamer->json['channel']['followers'],
                    'views' => $streamer->json['channel']['views'],
                ];

                if(Channel::where('exid', $exid)->count()>0)
                {
                    $channel = Channel::where('exid', $exid)->first();
                    $data['channel_id'] = $channel->id;
                    $data['game_id'] = $channel->game_id;
                }else{
                    $data['game_id'] = $this->getGameIdByTitle($streamer->json['channel']['game']);
                }


                if(!in_array($exid, $ids))
                    RatingChannel::create($data);

                $ids[] = $exid;
            }
        });
    }

    /**
     * @param $title
     * @return int
     */
    protected function getGameIdByTitle($title)
    {
        $games = Game::where('title', '=', $title);
        if($games->count()>0)
            return $games->first()->id;

        return 0;
    }

    protected function importFromExistChannels()
    {
        $channels = Channel::whereHas('user', function($q) {
                $q->where('fake', '<>', 1);
            })->get();

        foreach($channels as $channel)
        {
            if(RatingChannel::where('exid', $channel->exid)->count()==0)
            {
                $twitch = new TwitchHelper('r');
                if($data = $twitch->getChannel($channel->exid) && isset($data['_id']))
                {
                    RatingChannel::create([
                        'provider' => 'twitch',
                        'name' => $data['name'],
                        'url' => $data['url'],
                        'exid' => $data['_id'],
                        'json' => $data,
                        'followers' => $data['followers'],
                        'views' => $data['views'],
                        'channel_id' => $channel->id,
                        'game_id' => $channel->game_id
                    ]);
                }
            }
        }
    }
}