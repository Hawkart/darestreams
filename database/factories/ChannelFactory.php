<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Channel;
use App\Models\User;
use App\Models\Game;
use Faker\Generator as Faker;

$factory->define(Channel::class, function (Faker $faker) {

    $filepath = public_path('storage/channels');

    if(!File::exists($filepath))
        File::makeDirectory($filepath);

    $game = Game::inRandomOrder()->first();
    $user = User::doesntHave('channel')->first();

    try{
        $twitchClient = new \TwitchApi\TwitchApi([
            'client_id' => config('app.twitch_api_cid')
        ]);

        $twitchClient->setApiVersion(3);
        $data = $twitchClient->getChannel($user->nickname);
    } catch (\Exception $e) {
        $data = [];
    }

    return [
        'title' => $user->nickname,
        'logo' => !isset($data['logo']) ? $game->logo : $data['logo'],
        'overlay' => !isset($data['video_banner']) ? $game->logo : $data['video_banner'],
        'description' => !isset($data['status']) ?  $faker->paragraph(3, true) : $data['status'],
        'user_id'   => $user->id,
        'link'      => 'https://www.twitch.tv/'.$user->nickname,
        'game_id'   => $game->id,
        'provider' => 'twitch',
        'exid'  =>  $faker->numberBetween(1, 1000000),
        'views' => $faker->numberBetween(1, 1000),
    ];
});
