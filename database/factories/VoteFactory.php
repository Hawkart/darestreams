<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Task;
use App\Models\User;
use App\Models\Vote;
use Faker\Generator as Faker;
use App\Enums\VoteStatus;

$factory->define(Vote::class, function (Faker $faker) {
    return [
        'vote'    => VoteStatus::getRandomValue(),
        'task_id'   =>  function () {
            return Task::inRandomOrder()->first()->id;
        },
        'user_id'   =>  function () {
            return User::inRandomOrder()->first()->id;
        }
    ];
});