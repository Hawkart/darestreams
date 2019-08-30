<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Stream;
use App\Models\Channel;
use App\Models\Game;
use Faker\Generator as Faker;
use App\Enums\StreamStatus;

$factory->define(Stream::class, function (Faker $faker) {
    return [
        'title' => $faker->paragraph(1, true),
        'link'      => $faker->imageUrl(800, 600, 'cats', true),
        'start_at'  => $faker->dateTime(),
        'status'    => StreamStatus::getRandomValue(),
        'channel_id'   =>  function () {
            return Channel::inRandomOrder()->first()->id;
        },
        'game_id'   =>  function () {
            return Game::inRandomOrder()->first()->id;
        },
        'allow_task_before_stream' => $faker->boolean(),
        'allow_task_when_stream' => $faker->boolean(),
        'min_amount_task_before_stream' => $faker->numberBetween(1, 100),
        'min_amount_task_when_stream' =>  $faker->numberBetween(1, 100),
        'min_amount_donate_task_before_stream'=> $faker->numberBetween(1, 100),
        'min_amount_donate_task_when_stream'=>  $faker->numberBetween(1, 100),
        'quantity_donators'    => $faker->numberBetween(1, 10)
    ];
});
