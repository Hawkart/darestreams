<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Channel;
use App\Models\User;
use App\Models\Game;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Channel::class, function (Faker $faker) {
    $filepath = public_path('storage/channels');

    if(!File::exists($filepath))
        File::makeDirectory($filepath);

    $game = Game::inRandomOrder()->first();
    $user = User::doesntHave('channel')->first();

    return [
        'title' => $user->nickname,
        'logo' => $game->logo,
        'description' => $faker->paragraph(3, true),
        'user_id'   => $user->id,
        'slug'      => Str::slug($user->nickname, '_'),
        'link'      => 'https://www.twitch.tv/'.$user->nickname,
        'game_id'   => $game->id,
        'provider' => 'twitch',
        'exid'  =>  $faker->numberBetween(1, 1000000),
        'views' => $faker->numberBetween(1, 1000),
        'overlay' => $faker->image('public/storage/avatars',100, 100),
    ];
});