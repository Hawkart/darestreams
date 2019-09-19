<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Transaction;
use App\Models\Task;
use App\Models\Account;
use Faker\Generator as Faker;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;

$factory->define(Transaction::class, function (Faker $faker) {

    $task = Task::inRandomOrder()->first();
    $account = Account::inRandomOrder()->first();
    $amount = $faker->randomNumber(2);
    $type = TransactionType::getRandomValue();

    return [
        'amount'    => $amount,
        'money'     => $amount,
        'account_sender_id'  => function () use ($account) {
            return Account::where('id', '<>', $account->id)->inRandomOrder()->first();
        },
        'account_receiver_id'  => function () use ($account) {
            return $account->id;
        },
        'task_id'   =>  function () use ($task) {
            return $task->id;
        },
        'status' => $faker->randomElement([TransactionStatus::Holding, TransactionStatus::Completed]),
        'comment' => $faker->sentence(),
        'type' => $type,
        'exid' => $type==TransactionType::Deposit ? $faker->creditCardNumber : null,
        'payment' => $type==TransactionType::Deposit ? $faker->creditCardNumber : null
    ];
});
