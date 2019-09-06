<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\OAuthProvider;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(OAuthProvider::class, function (Faker $faker, $attributes) {

    echo $attributes['provider_user_id']."\r\n";

    return [
        'provider'          =>  'twitch',
        'provider_user_id'  =>  $attributes['provider_user_id'],
        'user_id'           =>  $attributes['user_id']
    ];
});
