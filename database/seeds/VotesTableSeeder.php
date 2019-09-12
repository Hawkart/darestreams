<?php

use Illuminate\Database\Seeder;
use App\Models\Vote;

class VotesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $items = factory(Vote::class, 100)->make();

        foreach ($items as $item) {
            repeat:
            try {
                $item->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $item = factory(Vote::class)->make();
                goto repeat;
            }
        }
    }
}