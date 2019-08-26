<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {

    $filepath = public_path('storage/avatars');

    if(!File::exists($filepath))
        File::makeDirectory($filepath);

    $nicknames = ['Kitboga', 'TimTheTatman', 'dota2ti_ru', 'UCCleague', 'GohaMedia', 'archangel_hs',
        'TpaBoMaH', 'aravay46', 'Rubius', 'mrfreshasian', 'Nixjke', 'unforgettabletv', 'NBA', 'TFBlade',
        'dakotaz', 'SimCopter1', 'koil', 'itsHafu', 'JoblessGarrett', 'EsfandTV', 'IWillDominate', 'h3x_TV', 'Mikasa_th'];

    return [
        'name' => $faker->name,
        'nickname' => strtolower($faker->randomElement($nicknames)),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'avatar' => $faker->image('public/storage/avatars',100, 100),
        'password' => bcrypt('secret'),
        'remember_token' => Str::random(10),
        'settings' => [
            'lang' => $faker->randomElement(['ru', 'en'])
        ]
    ];
});
