<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Channel;
use Faker\Generator as Faker;

$factory->define(Channel::class, function (Faker $faker, $attributes) {
    echo $attributes['title']."\r\n";
    return $attributes;
});
