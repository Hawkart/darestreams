<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\AdvCampaign;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(AdvCampaign::class, function (Faker $faker) {
    $filepath = public_path('storage/campaigns');

    if(!File::exists($filepath))
        File::makeDirectory($filepath);

    return [
        'logo' => $faker->image('public/storage/campaigns',400, 300),
        'title' => $faker->paragraph(1, true),
        'user_id'   =>  function () {
            return User::inRandomOrder()->first()->id;
        },
        'from' => $faker->dateTime(),
        'to' => $faker->dateTime(),
        'brand' => $faker->paragraph(1, true),
        'limit' => $faker->numberBetween(100, 10000),
    ];
});