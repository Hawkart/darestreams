<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Channel;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Channel::class, function (Faker $faker) {

    $filepath = public_path('storage/channels');

    if(!File::exists($filepath))
        File::makeDirectory($filepath);

    return [
        'title' => $faker->name,
        'logo' => $faker->image('public/storage/channels',400, 300),
        'description' => $faker->paragraph(3, true),
        'user_id'   =>  function () {
            return User::inRandomOrder()->first()->id;
        }
    ];
});
