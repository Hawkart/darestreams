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

        Commands\ImportGames::class,
        Commands\GetViewsForActiveStreams::class,
        Commands\MakePaymentsByStreams::class,
        Commands\UpdateStreamsStatus::class,
        Commands\CheckTaskInterval::class,
        Commands\FinishVotes::class,
        Commands\AdminNotifyNewStreams::class,

        Commands\ClearAndDelete\ClearSeederData::class,
        Commands\ClearAndDelete\DropTables::class,
        Commands\ClearAndDelete\DeleteUserAndData::class,
        Commands\ClearAndDelete\ClearDroppedTransactions::class,
        Commands\ClearAndDelete\ClearNotUsedTasksInFinishedStreams::class,

        Commands\Ones\TransferChannelLogoToUser::class,
        Commands\Ones\UpdateStreamTasksDesc::class,
        Commands\Ones\GetChannelVideosOnTwitch::class,
        Commands\Ones\CheckAlert::class,
        Commands\Ones\GetLinksVideosFromTwitch::class,
        Commands\Ones\GetLiveStreams::class,

        Commands\Rating\ParseStreamers::class,
        Commands\Rating\UpdateRatingChannels::class,
        Commands\Rating\CalculateRatingTop::class,
        Commands\Rating\SyncStatChannels::class
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
        $schedule->command('streams:notify_admins')->everyFiveMinutes();
        $schedule->command('streams:pay_donations')->daily()->timezone('America/New_York')->at('02:00');
        $schedule->command('tasks:check_interval')->everyMinute();
        $schedule->command('tasks:clear_not_used')->daily()->timezone('America/New_York')->at('03:00');
        $schedule->command('transactions:clear_dropped')->daily()->timezone('America/New_York')->at('02:00');
        $schedule->command('votes:finish')->everyMinute();


        $schedule->command('twitch:get_live_streams')->everyTenMinutes();
        //$schedule->command('streamers:parse')->hourly();
        //$schedule->command('stat:channels_update')->cron('* * * * 1,2,3,4');
        //$schedule->command('stat:calculate_top')->fridays()->everyFiveMinutes();
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