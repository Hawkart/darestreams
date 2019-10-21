<?php

namespace App\Console\Commands\Rating;

use App\Models\Streamer;
use Illuminate\Console\Command;
use Exporter;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ParseStreamers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streamers:parse {--export=}';

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

        $streamers = Streamer::all()->pluck('name')->toArray();

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
                    if(!in_array($name, $streamers))
                    {
                        Streamer::create([
                            'name' => $name,
                            'json' => $stream
                        ]);

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
        $streamers = Streamer::all();

        $collection = [];
        foreach($streamers as $key => $streamer)
        {
            $channel = $streamer->json['channel'];

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
}
