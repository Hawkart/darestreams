<?php

use Illuminate\Database\Seeder;
use App\Models\Channel;
use App\Models\User;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach($users as $user)
        {
            factory(Channel::class)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
