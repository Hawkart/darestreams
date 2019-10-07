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
        Commands\GetViewsForActiveStreams::class,
        Commands\MakePaymentsByStreams::class,
        Commands\UpdateStreamsStatus::class,
        Commands\CheckTaskInterval::class,
        Commands\FinishVotes::class,
        Commands\ClearDroppedTransactions::class,
        Commands\ParseStreamers::class,
        Commands\TransferChannelLogoToUser::class,
        Commands\UpdateStreamTasksDesc::class,
        Commands\DeleteUserAndData::class,
        Commands\ClearNotUsedTasksInFinishedStreams::class,
        Commands\GetChannelVideosOnTwitch::class,
        Commands\UpdateRatingChannels::class,
        Commands\CalculateRatingTop::class,
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
        $schedule->command('streams:update_status')->everyMinute();
        $schedule->command('streams:get_views')->everyFiveMinutes();
        $schedule->command('streams:pay_donations')->daily()->timezone('America/New_York')->at('02:00');
        $schedule->command('tasks:check_interval')->everyMinute();
        $schedule->command('tasks:clear_not_used')->daily()->timezone('America/New_York')->at('03:00');
        $schedule->command('transactions:clear_dropped')->daily()->timezone('America/New_York')->at('02:00');
        $schedule->command('votes:finish')->everyMinute();
        $schedule->command('streamers:parse')->hourly();

        $schedule->command('stat:channels_update')->cron('* * * * 1,2,3,4');
        $schedule->command('stat:calculate_top')->fridays()->timezone('America/New_York')->at('02:00');
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
