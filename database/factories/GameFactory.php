<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Game;
use Faker\Generator as Faker;

$factory->define(Game::class, function (Faker $faker) {

    $filepath = storage_path('games');

    if(!File::exists($filepath))
        File::makeDirectory($filepath);

    return [
        'title' => $faker->unique()->company,
        'title_short' => $faker->unique()->company." ".$faker->companySuffix,
        'logo' => $faker->image($filepath,400, 300),
        'logo_small' => $faker->image($filepath,100, 100),
    ];
});
