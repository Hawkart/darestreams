<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard(); // Disable mass assignment

        /*$this->call(UsersTableSeeder::class);
        $this->call(OauthProvidersTableSeeder::class);
        $this->call(GamesTableSeeder::class);
        $this->call(StreamsTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
        $this->call(MessagesTableSeeder::class);
        $this->call(NotificationsTableSeeder::class);*/

        Model::reguard(); // Enable mass assignment
    }
}
