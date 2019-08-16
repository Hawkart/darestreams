<?php

namespace App\Jobs;

use App\Models\Channel;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class GetUserChannel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $user;
    protected $id;
    protected $provider;

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
                $twitchClient = new \TwitchApi\TwitchApi([
                    'client_id' => config('app.twitch_api_cid')
                ]);

                try {
                    $data = $twitchClient->getChannel($this->id);

                    $ch = [
                        'exid' => $data['_id'],
                        'user_id' => $this->user->id,
                        "provider" => $this->provider,
                        "title" => $data['display_name'],
                        "link" => $data['url'],
                        "game_id" => (!empty($data['game'])) ? $this->getGameIdByTitle($data['game']) : 0,
                        "description" => $data['status'],
                        'views' => $data['views'],
                        'logo' => $data['logo'],
                    ];

                    Channel::create($ch);

                    //user lang update
                    $settings = $this->user->settings;
                    $settings['lang'] = $data['language'];

                    $this->user->update([
                        'settings' => $settings
                    ]);
                } catch (\Exception $e) {

                    Log::info('GetUserChannel', [
                        'error' => $e->getMessage(),
                        'file' => __FILE__,
                        'line' => __LINE__
                    ]);
                }
            }
        }
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
