<?php

namespace App\Jobs;

use App\Acme\Helpers\TwitchHelper;
use App\Models\Channel;
use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetUserChannel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $user;
    public $id;
    public $provider;

    /**
     * GetUserChannel constructor.
     * @param $user
     * @param $socialId
     * @param $provider
     */
    public function __construct($user, $socialId, $provider)
    {
        $this->user = $user;
        $this->id = $socialId;
        $this->provider = $provider;
    }

    /**
     * @throws \TwitchApi\Exceptions\ClientIdRequiredException
     */
    public function handle()
    {
        if(empty($this->user->channel))
        {
            if($this->provider == 'twitch')
            {
                $twitch = new TwitchHelper();
                if($data = $twitch->getChannel($this->id, true) && isset($data['_id']))
                {
                    $ch = [
                        'exid' => $data['_id'],
                        'user_id' => $this->user->id,
                        "provider" => $this->provider,
                        "title" => $data['name'],
                        "link" => $data['url'],
                        "game_id" => (!empty($data['game'])) ? $this->getGameIdByTitle($data['game']) : 0,
                        "description" => $data['description'] ? $data['description'] : "",
                        'views' => $data['views'],
                        'logo' => $data['logo'],
                        'overlay' => isset($data['video_banner']) ? $data['video_banner'] : ''
                    ];

                    Channel::create($ch);

                    $this->addStatChannel($data);

                    //user lang update
                    $settings = $this->user->settings;
                    $settings['lang'] = $data['language'];

                    $this->user->update([
                        'settings' => $settings
                    ]);
                }
            }
        }
    }

    /**
     * @param $data
     */
    public function addStatChannel($data)
    {
        $sch = \App\Models\Rating\Channel::firstOrNew(['exid' => $data['_id']]);
        $sch->name = $data['name'];
        $sch->provider = 'twitch';
        $sch->url = $data['url'];
        $sch->json = $data;
        $sch->exist = true;
        $sch->followers = $data['followers'];
        $sch->views = $data['views'];
        $sch->save();
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

        return 1;
    }
}
