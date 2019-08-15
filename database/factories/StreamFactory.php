<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Stream;
use App\Models\Channel;
use App\Models\Game;
use Faker\Generator as Faker;

$factory->define(Stream::class, function (Faker $faker) {
    return [
        'link'      => $faker->imageUrl(800, 600, 'cats', true),
        'start_at'  => $faker->dateTime(),
        'status'    => 0,
        'channel_id'   =>  function () {
            return Channel::inRandomOrder()->first()->id;
        },
        'game_id'   =>  function () {
            return Game::inRandomOrder()->first()->id;
        },
        'quantity_donators'    => $faker->numberBetween(1, 10),
        'quantity_donations'    => $faker->randomFloat(2, 10, 1000),
        'amount_donations'    => $faker->randomFloat(2, 100, 1000),
    ];
});
