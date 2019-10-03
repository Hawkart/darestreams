<?php

namespace App\Acme\Helpers;

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
}