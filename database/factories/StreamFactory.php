<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Stream;
use App\Models\User;
use App\Models\Game;
use Faker\Generator as Faker;

$factory->define(Stream::class, function (Faker $faker) {
    return [
        'link'      => $faker->imageUrl(800, 600, 'cats', true),
        'start_at'  => $faker->dateTime(),
        'status'    => 0,
        'user_id'   =>  function () {
            return User::inRandomOrder()->first()->id;
        },
        'game_id'   =>  function () {
            return Game::inRandomOrder()->first()->id;
        }
    ];
});
