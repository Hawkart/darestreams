<?php

namespace App\Console\Commands;

use App\Enums\StreamStatus;
use App\Models\Stream;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetViewsForActiveStreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streams:get_views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get views for active streams.';

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


        $streams = Stream::where('status', StreamStatus::Active)
            ->whereHas('channel', function($q) {
                $q->where('provider', 'twitch');
            })
            ->with(['channel'])
            ->orderByDesc('created_at')
            ->get();

        if(count($streams)>0)
        {
            foreach($streams as $stream)
            {
                $channel = $stream->channel;

                $twitch = new TwitchHelper();

                if($data = $twitch->getStreamByUser($channel->exid) && isset($data['stream']))
                {
                    $channel->update([
                        "description" => $data['stream']['channel']['description'] ? $data['stream']['channel']['description'] : "",
                        'views' => $data['stream']['channel']['views'],
                    ]);

                    $stream->update([
                        'views' =>  $data['stream']['viewers'],
                        'preview' =>  $data['stream']['preview']['large']
                    ]);
                }

                sleep(2);
            }
        }

        $bar->finish();
    }
}