<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Stream;
use App\Models\Task;
use App\Models\User;
use Faker\Generator as Faker;
use App\Enums\TaskStatus;

$factory->define(Task::class, function (Faker $faker) {

    $is_superbowl = $faker->boolean;

    return [
        'small_desc'            => $faker->paragraph(1, true),
        'full_desc'             => $faker->paragraph(3, true),
        'is_superbowl'          => $is_superbowl,
        'interval_time'         => $faker->numberBetween(0, 50),
        'status'                => $faker->numberBetween(0, 6),
        'min_donation'          => $faker->numberBetween(1, 100),
        'user_id'   =>  function () {
            return User::inRandomOrder()->first()->id;
        },
        'stream_id'   =>  function () {
            return Stream::inRandomOrder()->first()->id;
        }
    ];
});
