<?php

use Illuminate\Database\Seeder;
use App\Models\Channel;
use App\Models\User;

class ChannelsTableSeeder extends Seeder
{
    public function run()
    {
        $twitchClient = new \TwitchApi\TwitchApi([
            'client_id' => config('app.twitch_api_cid')
        ]);

        $users = User::all();
        foreach($users as $user)
        {
            $twuser = $user->oauthProviders()->where('provider', 'twitch')->first();

            try {
                $data = $twitchClient->getChannel($twuser->provider_user_id);

                if(isset($data['_id']))
                {
                    factory(Channel::class)->create([
                        'user_id' => $user->id,
                        'data' => $data
                    ]);
                }

            } catch (\Exception $e) {
                echo $e->getMessage()."\r\n";
            }

            sleep(2);
        }
    }
}
