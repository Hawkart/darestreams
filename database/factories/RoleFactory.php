<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\TCG\Voyager\Models\Role::class, function (Faker $faker) {

    return [
        'name'         => $faker->realText(100),
        'display_name' => $faker->realText(100),
    ];
});