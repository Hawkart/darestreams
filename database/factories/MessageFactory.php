<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Thread;
use App\Models\User;
use \App\Models\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'body'    => $faker->paragraph(rand(1,3), true),
        'user_id'   =>  function () {
            return User::inRandomOrder()->first()->id;
        },
        'thread_id'   =>  function () {
            return Thread::inRandomOrder()->first()->id;
        }
    ];
});
