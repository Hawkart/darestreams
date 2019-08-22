<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $items = factory(User::class, 10)->make();

        foreach ($items as $item) {
            repeat:
            try {
                $item->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $item = factory(User::class)->make();
                goto repeat;
            }
        }
    }
}
