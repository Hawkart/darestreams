<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\User;
use App\Models\Notification;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker) {
    return [
        "id" => $faker->uuid,
        "type" => "App\\Notifications\\".$faker->randomElement(['NewMessage', 'NewStream', 'NewFollower']),
        "notifiable_type" => "App\Models\User",
        "notifiable_id" => function () {
            return User::inRandomOrder()->first()->id;
        },
        "data" => [
            "any" => $faker->paragraph(3, true)
        ]
    ];
});
