<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Stream;
use App\Models\Task;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {

    $interval_until_end = $faker->boolean;
    $is_superbowl = $faker->boolean;

    return [
        'description'           => $faker->paragraph(3, true),
        'is_superbowl'          => $is_superbowl,
        'interval_until_end'    => $interval_until_end,
        'interval_time'         => $interval_until_end ? 0 : $faker->numberBetween(10, 50),
        'min_amount'            => $faker->numberBetween(10, 500),
        'min_amount_superbowl'  => $is_superbowl ? 0 : $faker->numberBetween(200, 1000),
        'status'                => 0,
        'check_vote'            => $faker->boolean,
        'interval_finished'     => $faker->boolean,
        'user_id'   =>  function () {
            return User::inRandomOrder()->first()->id;
        },
        'stream_id'   =>  function () {
            return Stream::inRandomOrder()->first()->id;
        }
    ];
});
