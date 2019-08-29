<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ClearSeederData::class,
        Commands\DropTables::class,
        Commands\ImportGames::class,
        Commands\ImportChannelsViews::class,
        Commands\CountTaskResults::class,
        Commands\UpdateStreamsStatus::class,
        Commands\CheckTaskInterval::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('games:import')->weeklyOn(1);
        $schedule->command('channels:get_views')->hourly();
        $schedule->command('tasks:check_interval')->everyMinute();
        $schedule->command('streams:update_status')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
