<?php

use Illuminate\Database\Seeder;
use App\Models\OAuthProvider;
use NewTwitchApi\NewTwitchApi;
use App\Models\User;

class OauthProvidersTableSeeder extends Seeder
{
    public function run()
    {
        $clientId = config('app.twitch_api_key');
        $clientSecret = config('app.twitch_api_secret');

        $helixGuzzleClient = new \NewTwitchApi\HelixGuzzleClient($clientId);
        $newTwitchApi = new NewTwitchApi($helixGuzzleClient, $clientId, $clientSecret);

        $users = User::all();
        foreach($users as $user)
        {
            try {
                $response = $newTwitchApi->getUsersApi()->getUserByUsername($user->nickname);
                $responseContent = json_decode($response->getBody()->getContents());

                if(isset($responseContent->data[0]) && $twuser = $responseContent->data[0])
                {
                    factory(OAuthProvider::class)->create([
                        'user_id' => $user->id,
                        'provider_user_id'  =>  $twuser->id,
                    ]);
                }
            } catch (\Exception $e) {
                echo $e->getMessage()."\r\n";
            }

            sleep(2);
        }
    }
}
