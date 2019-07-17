<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Transaction;
use App\Models\Task;
use App\Models\Account;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {

    $task = Task::inRandomOrder()->first();
    $receiver = $task->stream->user->account;

    return [
        'amount'    => $faker->randomFloat(2, 100, 1000),
        'account_sender_id'  => function () use ($receiver) {
            return Account::where('id', '<>', $receiver->id)->inRandomOrder()->first();
        },
        'account_receiver_id'  => function () use ($receiver) {
            return $receiver->id;
        },
        'task_id'   =>  function () use ($task) {
            return $task->id;
        }
    ];
});
