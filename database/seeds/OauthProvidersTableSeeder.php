<?php

use Illuminate\Database\Seeder;
use App\Models\OAuthProvider;

class OauthProvidersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::all();
        foreach($users as $user)
        {
            factory(OAuthProvider::class)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
