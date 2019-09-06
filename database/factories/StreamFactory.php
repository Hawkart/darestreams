<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Stream;
use App\Models\Channel;
use Faker\Generator as Faker;
use App\Enums\StreamStatus;

$factory->define(Stream::class, function (Faker $faker, $attributes) {

    $channel = Channel::findOrFail($attributes['channel_id']);

    $allow = $faker->boolean();

    return [
        'title' => $faker->paragraph(1, true),
        'link'      => $channel->link,
        'start_at'  => $faker->dateTime(),
        'status'    => StreamStatus::Active,
        'channel_id'   =>  $channel->id,
        'game_id'   =>  $channel->game_id,
        'allow_task_before_stream' => $allow,
        'allow_task_when_stream' => !$allow,
        'min_amount_task_before_stream' => $faker->numberBetween(1, 100),
        'min_amount_task_when_stream' =>  $faker->numberBetween(1, 100),
        'min_amount_donate_task_before_stream'=> $faker->numberBetween(1, 100),
        'min_amount_donate_task_when_stream'=>  $faker->numberBetween(1, 100),
        'quantity_donators'    => $faker->numberBetween(1, 10),
        'views' => $channel->views
    ];
});
