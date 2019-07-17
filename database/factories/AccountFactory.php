<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Account;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'amount'    => $faker->randomFloat(2, 100, 100000),
        'currency'  => 'USD',
        'user_id'   =>  function () {
            return User::inRandomOrder()->first()->id;
        }
    ];
});
