<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SyncStreamByTwitch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $stream;

    /**
     * SyncStreamByTwitch constructor.
     * @param $stream
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    /**
     * @throws \TwitchApi\Exceptions\ClientIdRequiredException
     */
    public function handle()
    {
        $twitchClient = new \TwitchApi\TwitchApi([
            'client_id' => config('app.twitch_api_cid')
        ]);

        try {
            $data = $twitchClient->getChannelVideos($this->stream->channel->exid, 1, 0, 'archives');

            if(isset($data['videos']) && count($data['_total'])>0)
            {
                $video = $data['videos'][0];
                $this->stream->update([
                   'views' => $video['views'],
                   'link' => $video['_links']['self']
                ]);
            }
        } catch (\Exception $e) {

            Log::info('SyncStreamByTwitch', [
                'error' => $e->getMessage(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);
        }
    }
}