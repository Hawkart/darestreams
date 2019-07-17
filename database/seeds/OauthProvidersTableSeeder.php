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
        factory(OAuthProvider::class, 10)->create();
    }
}
