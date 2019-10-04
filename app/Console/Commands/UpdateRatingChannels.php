<?php

namespace App\Console\Commands;

use App\Acme\Helpers\TwitchHelper;
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
                        'views' => $data['views'],
                        'exist' => true
                    ]);
                }

                sleep(1);
            }
        }
    }

    protected function importFromStreamers()
    {
        Streamer::chunk(200, function ($streamers)
        {
            foreach($streamers as $streamer)
            {
                $exid = $streamer->json['_id'];

                $data = [
                    'provider' => 'twitch',
                    'name' => $streamer->name,
                    'url' => $streamer->json['channel']['url'],
                    'exid' => $streamer->json['_id'],
                    'json' => $streamer->json,
                    'followers' => $streamer->json['channel']['followers'],
                    'views' => $streamer->json['channel']['views'],
                ];

                if(Channel::where('exid', $exid)->count()>0)
                    $data['exist'] = true;

                RatingChannel::create($data);
            }
        });
    }

    protected function importFromExistChannels()
    {
        $channels = Channel::all();

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
                        'exist' => true
                    ]);
                }
            }
        }
    }
}