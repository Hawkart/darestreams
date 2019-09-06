<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\OAuthProvider;
use App\Models\User;
use Faker\Generator as Faker;
use NewTwitchApi\NewTwitchApi;

$factory->define(OAuthProvider::class, function (Faker $faker, $attributes) {

    $user = User::findOrFail($attributes['user_id']);

    $clientId = config('app.twitch_api_key');
    $clientSecret = config('app.twitch_api_secret');

    $helixGuzzleClient = new \NewTwitchApi\HelixGuzzleClient($clientId);
    $newTwitchApi = new NewTwitchApi($helixGuzzleClient, $clientId, $clientSecret);

    try {
        $response = $newTwitchApi->getUsersApi()->getUserByUsername($user->nickname);
        $responseContent = json_decode($response->getBody()->getContents());

        if($twuser = $responseContent->data[0])
        {
            return [
                'provider'          =>  'twitch',
                'provider_user_id'  =>  $twuser->id,
                'user_id'           =>  $attributes['user_id']
            ];
        }else{
            return [];
        }

    } catch (GuzzleException $e) {
        echo $e->getMessage()."\r\n";
    }
});
