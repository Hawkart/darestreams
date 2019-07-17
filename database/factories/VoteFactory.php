<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Task;
use App\Models\User;
use App\Models\Vote;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'vote'    => $faker->numberBetween(0, 2),
        'task_id'   =>  function () {
            return Task::inRandomOrder()->first()->id;
        },
        'user_id'   =>  function () {
            return User::inRandomOrder()->first()->id;
        }
    ];
});
