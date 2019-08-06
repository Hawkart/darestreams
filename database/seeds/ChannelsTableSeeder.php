<?php

use Illuminate\Database\Seeder;
use App\Models\Channel;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        factory(Channel::class, 200)->create();
    }
}
