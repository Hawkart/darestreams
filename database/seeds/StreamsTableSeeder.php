<?php

use Illuminate\Database\Seeder;
use App\Models\Stream;
use App\Models\Channel;

class StreamsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $channels = Channel::all();
        foreach($channels as $channel)
        {
            factory(Stream::class)->create([
                'channel_id' => $channel->id,
            ]);
        }
    }
}
