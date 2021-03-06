<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {

    $filepath = public_path('storage/avatars');

    if(!File::exists($filepath))
        File::makeDirectory($filepath);

    return [
        'name' => $faker->name,
        'nickname' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'avatar' => $faker->image('public/storage/avatars',100, 100),
        'password' => bcrypt('secret'),
        'remember_token' => Str::random(10),
        'role_id' => 2,
        'settings' => [
            'lang' => $faker->randomElement(['ru', 'en'])
        ]
    ];
});