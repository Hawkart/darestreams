<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\OAuthProvider;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(OAuthProvider::class, function (Faker $faker) {
    return [
        'provider'          =>  $faker->randomElement(['facebook', 'vkontakte', 'google', 'twitch', 'youtube']),
        'provider_user_id'  =>  $faker->creditCardNumber,
        'user_id'           =>  function () {
            return User::inRandomOrder()->first()->id;
        }
    ];
});
