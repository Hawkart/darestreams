<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Channel;
use App\Models\User;
use App\Models\Game;
use Faker\Generator as Faker;

$factory->define(Channel::class, function (Faker $faker, $attributes) {

    $filepath = public_path('storage/channels');

    if(!File::exists($filepath))
        File::makeDirectory($filepath);

    $user = User::findOrFail($attributes['user_id']);
    $twuser = $user->oauthProviders()->where('provider', 'twitch')->first();

    $twitchClient = new \TwitchApi\TwitchApi([
        'client_id' => config('app.twitch_api_cid')
    ]);

    try {
        $data = $twitchClient->getChannel($twuser->provider_user_id);

        $game_id = 1;
        $games = Game::where('title', '=', $data['game']);
        if($games->count()>0)
            $game_id = $games->first()->id;

        return [
            'exid' => $data['_id'],
            'user_id' => $user->id,
            "provider" => 'twtich',
            "title" => $data['name'],
            "link" => $data['url'],
            "game_id" => $game_id,
            "description" => $data['description'] ? $data['description'] : "",
            'views' => $data['views'],
            'logo' => $data['logo'],
            'overlay' => isset($data['video_banner']) ? $data['video_banner'] : ''
        ];

    } catch (\Exception $e) {
        echo $e->getMessage()."\r\n";
        return [];
    }
});
