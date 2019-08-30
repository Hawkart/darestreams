<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {

    $filepath = public_path('storage/avatars');

    if(!File::exists($filepath))
        File::makeDirectory($filepath);

    $nicknames = ['amouranth','hardgamechannel','gabepeixe','sweet_anita','texaswildlife','violettavalery','gaules',
        'asmr_kotya','exbc','rocketbeanstv','copykat_','yoda','shroud','noway4u_sir','stpeach',"gladiatorpwnz","jesusavgn",
        "olyashaa","tenderlybae","tati","mira","ahrinyan","kuplinov","denly","happasc2","modestal","sorabi_","zanuda",
        "gufovicky","lasqa","olesyabulletka","mihalina_","hellyeahplay","scr3amqueen","beastqt","windy31","punshipun",
        "vika_karter","mob5tertv","ant1ka","elwycco","tangerin","kittyklawtv","dawgdebik","gavrilka","playbetterpro",
        "jamclub","gagatun","ciklonica","morphia","nemagiaru","cemka","saddrama","segall","thethomasavengers","fruktozka",
        "murochka_ua","ellvi","kyxnya","zakvielchannel","morganrandom","tiggra","bloody_elf","inmateoo","dariya_willis",
        "dinablin","playwithserch","promotive","b_u_l_o_c_h_k_a","unique","adam1tbc","romanovalera","kati","asmr_kotya",
        "sholidays","leyagornaya","rootyasha","lucifer__chan","stopannya","dimaoneshot","msmaggiezolin","revnyasha",
        "mikerina","nelyaray","eveliinushka","bulochkaaa","yuuechka","olesha","elfiona","insize","ksyasha","jointime",
        "lorinefairy","panteleev","ameriahime","delaylamy","joskiyokda","dtfru","leniniw","by_owl","yuki2yuki","5live_bgd",
        "shtyr","johnylemonade","theveronicous","steel","busya18plus"];

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
