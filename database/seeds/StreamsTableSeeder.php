<?php

use Illuminate\Database\Seeder;
use App\Models\Stream;

class StreamsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        factory(Stream::class, 1000)->create();
    }
}
