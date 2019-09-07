<?php

use Illuminate\Database\Seeder;
use App\Models\Channel;
use App\Models\User;
use App\Models\Game;

class ChannelsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        foreach($users as $user)
        {
            $twuser = $user->oauthProviders()->where('provider', 'twitch')->first();

            if($twuser)
            {
                try {
                    $twitchClient = new \TwitchApi\TwitchApi([
                        'client_id' => config('app.twitch_api_cid')
                    ]);

                    $data = $twitchClient->getChannel($twuser->provider_user_id);

                    if(isset($data['_id']))
                    {
                        $game_id = 1;
                        $games = Game::where('title', '=', $data['game']);
                        if($games->count()>0)
                            $game_id = $games->first()->id;

                        factory(Channel::class)->create([
                            'exid' => $data['_id'],
                            'user_id' => $user->id,
                            "provider" => 'twitch',
                            "title" => $data['name'],
                            "link" => $data['url'],
                            "game_id" => $game_id,
                            "description" => $data['description'] ? $data['description'] : "",
                            'views' => $data['views'],
                            'logo' => $data['logo'],
                            'overlay' => isset($data['video_banner']) ? $data['video_banner'] : ''
                        ]);
                    }

                } catch (\Exception $e) {
                    echo $e->getMessage()."\r\n";
                }
            }

            sleep(2);
        }
    }
}
