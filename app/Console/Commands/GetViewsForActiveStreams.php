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

        $twitchClient = new \TwitchApi\TwitchApi([
            'client_id' => config('app.twitch_api_cid')
        ]);

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
                try {
                    $channel = $stream->channel;

                    if($data = $twitchClient->getStreamByUser($channel->exid)) //$stream->channel->user->nickname
                    {
                        if(!empty($data['stream']))
                        {
                            $channel->update([
                                "description" => $data['stream']['channel']['description'] ? $data['stream']['channel']['description'] : "",
                                'views' => $data['stream']['channel']['views'],
                            ]);

                            $stream->update(['views' =>  $data['stream']['viewers']]);

                            echo $stream->id. " = ".$data['stream']['viewers']."\r\n";
                        }
                    }


                } catch (\Exception $e) {
                    echo $e->getMessage()."\r\n";
                    Log::info('GetViewsForActiveStreams', [
                        'error' => $e->getMessage(),
                        'file' => __FILE__,
                        'line' => __LINE__
                    ]);
                }

                sleep(2);
            }
        }

        $bar->finish();
    }
}