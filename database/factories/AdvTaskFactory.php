<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\AdvCampaign;
use App\Models\AdvTask;
use Faker\Generator as Faker;

$factory->define(AdvTask::class, function (Faker $faker) {

    return [
        'campaign_id'   =>  function () {
            return AdvCampaign::inRandomOrder()->first()->id;
        },
        'small_desc'            => $faker->paragraph(1, true),
        'full_desc'             => $faker->paragraph(3, true),
        'limit' => $faker->numberBetween(100, 10000),
        'price' => $faker->numberBetween(1, 10),
        'min_rating' => $faker->numberBetween(1, 10),
        'type' => $faker->numberBetween(0, 1),
    ];
});