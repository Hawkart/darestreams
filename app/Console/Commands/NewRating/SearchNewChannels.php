<?php

namespace App\Console\Commands\NewRating;

use App\Models\Channel;
use App\Models\Game;
use App\Models\Rating\Channel as RatingChannel;
use App\Models\Streamer;
use Illuminate\Console\Command;
use Exporter;
use File;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SearchNewChannels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new-rating:parse-streamers {--export=} {--import=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get streamers from twitch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \TwitchApi\Exceptions\ClientIdRequiredException
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(100);

        if(!empty($this->option('export'))) {
            $this->info("Exporting...");
            $this->export();
            dd("done!!!");
        }

        if(!empty($this->option('import')))
        {
            $this->info("Importing from streamers table to stat_channels...");
            $this->ChannelsDeleteDuplicate();
            dd(1);
            $this->importFromStreamers();
            dd("done!!!");
        }

        $channels = RatingChannel::all()->pluck('name')->toArray();

        $twitchClient = new \TwitchApi\TwitchApi([
            'client_id' => config('app.twitch_api_cid')
        ]);

        $count = 0;
        $limit = 100;
        $offset = 0;

        do{
            try {
                $responseTwitch = $twitchClient->getLiveStreams(null, null, 'ru', 'live', (int)$limit, (int)$offset, 'ru');

                if(!isset($responseTwitch['_total'])) break;

                $total = intval($responseTwitch['_total']);
                $streams = $responseTwitch["streams"];

                foreach($streams as $stream)
                {
                    $name = $stream['channel']['name'];

                    if(!in_array($name, $channels))
                    {
                        $this->CreateChannel($stream);
                        $this->info("Count=".$count." Nick = ".$name);
                    }
                }

                $count+= count($streams);
                $offset+= $limit;
                sleep(2);

            } catch (\Exception $e) {

                $this->error($e->getMessage());

                Log::info('ParseStreamers', [
                    'error' => $e->getMessage(),
                    'file' => __FILE__,
                    'line' => __LINE__
                ]);
            }

        } while ($count<$total);

        $bar->finish();
    }

    private function export()
    {
        $channels = RatingChannel::all();

        $collection = [];
        foreach($channels as $key => $ch)
        {
            $channel = $ch->json;

            if($key==0)
            {
                $fields = [];
                foreach($channel as $field => $value)
                    $fields[] = $field;

                $collection[] = $fields;
            }

            $collection[] = $channel;
        }

        $collection = collect($collection);

        $dir = 'public';
        Storage::makeDirectory($dir);
        $filename = "streamers".date("d_m_Y").".xlsx";
        Storage::disk('local')->put($dir.'/'.$filename, '');
        $path = storage_path('app/'.$dir.'/'.$filename);

        $exporter = Exporter::make('Excel');
        $exporter->load($collection);
        $exporter->save($path);

        $this->info("path=".$path);
    }

    protected function ChannelsDeleteDuplicate()
    {
        $ids = [];
        $deletes = [];
        foreach(RatingChannel::all() as $channel)
        {
            if(!in_array($channel->exid, $ids))
            {
                $ids[] = $channel->exid;
            }else{
                $deletes[] = $channel->id;
            }
        }

        dd($deletes);
        dd(RatingChannel::whereIn('id', $deletes)->count());
    }

    protected function importFromStreamers()
    {
        $names = RatingChannel::all()->pluck('name')->toArray();

        Streamer::whereNotIn('name', $names)->chunk(500, function ($streamers)
        {
            foreach($streamers as $streamer)
            {
                $this->CreateChannel($streamer->json);
            }
        });
    }

    public function CreateChannel($stream)
    {
        $exid = $stream['channel']['_id'];

        $data = [
            'provider' => 'twitch',
            'name' => $stream['channel']['name'],
            'url' => $stream['channel']['url'],
            'exid' => $exid,
            'json' => $stream['channel'],
            'followers' => $stream['channel']['followers'],
            'views' => $stream['channel']['views'],
        ];

        //check connection with dare channels
        $channels = Channel::where('exid', $exid);
        if($channels->count()>0)
        {
            $channel = $channels->first();
            $data['channel_id'] = $channel->id;
            $data['game_id'] = $channel->game_id;
        }else{
            $data['game_id'] = $this->getGameIdByTitle($stream['channel']['game']);
        }

        //check exist as statistic's channel
        //if(RatingChannel::where('exid', $exid)->count()==0)
        //{
            $result = RatingChannel::create($data);

            echo $result->id."\r\n";
        //}

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
}