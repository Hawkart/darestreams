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

        \Artisan::call('games:import', []);

        $this->call(UsersTableSeeder::class);
        $this->call(OauthProvidersTableSeeder::class);
        $this->call(ChannelsTableSeeder::class);
        DB::table('accounts')->update(['amount' => 1000000]);
        $this->call(StreamsTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
        $this->call(MessagesTableSeeder::class);
        $this->call(NotificationsTableSeeder::class);
        $this->call(VotesTableSeeder::class);

        Model::reguard(); // Enable mass assignment
    }
}
