<?php

namespace App\Jobs;

use App\Acme\Helpers\TwitchHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SyncStreamByTwitch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $stream;

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
        $twitch = new TwitchHelper();
        $data = $twitch->getChannelVideos($this->stream->channel->exid, 1, 0, 'archive');

        if(!empty($data) && isset($data['videos']) && count($data['_total'])>0)
        {
            $video = $data['videos'][0];

            $this->stream->update([
                'views' => $video['views'],
                'link' => $video['_links']['self']
            ]);
        }
    }
}