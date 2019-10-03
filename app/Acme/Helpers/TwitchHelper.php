<?php

namespace App\Acme\Helpers;
use Illuminate\Support\Facades\Log;

class TwitchHelper
{
    protected $client;

    public function __construct()
    {
        $this->client = new \TwitchApi\TwitchApi([
            'client_id' => config('app.twitch_api_cid')
        ]);
    }

    public function getChannelVideos($channel_id, $limit, $offset, $type)
    {
        try {
            return $this->client->getChannelVideos($channel_id, $limit, $offset, $type);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getChannel($channel_id, $log = false)
    {
        try {
            return $this->client->getChannel($channel_id);
        } catch (\Exception $e) {

            if($log)
                Log::info('GetChannel', [
                    'error' => $e->getMessage(),
                    'file' => __FILE__,
                    'line' => __LINE__
                ]);

            return [];
        }
    }

    public function getStreamByUser($channel_id)
    {
        try {
            return $this->client->getStreamByUser($channel_id);
        } catch (\Exception $e) {

            return [];
        }
    }

    protected function log()
    {

    }
}