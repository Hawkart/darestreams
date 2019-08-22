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
        $items = factory(Channel::class, 10)->make();

        foreach ($items as $item) {
            repeat:
            try {
                $item->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $item = factory(Channel::class)->make();
                goto repeat;
            }
        }
    }
}
