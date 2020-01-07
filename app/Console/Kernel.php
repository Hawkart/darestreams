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
        Commands\MakePaymentsByStreams::class,
        Commands\UpdateStreamsStatus::class,
        Commands\CheckTaskInterval::class,
        Commands\FinishVotes::class,

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
        Commands\Ones\BugfixRatingGameChannel::class,

        Commands\SpeechRecognize::class,

        //New Rating
        Commands\NewRating\SearchNewChannels::class,
        Commands\NewRating\GetLiveStreams::class,
        Commands\NewRating\CalculateTop::class,
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
        $schedule->command('streams:pay_donations')->daily()->timezone('America/New_York')->at('02:00');
        $schedule->command('tasks:check_interval')->everyMinute();
        $schedule->command('tasks:clear_not_used')->daily()->timezone('America/New_York')->at('03:00');
        $schedule->command('transactions:clear_dropped')->daily()->timezone('America/New_York')->at('02:00');
        $schedule->command('votes:finish')->everyMinute();

        $schedule->command('twitch:get_live_streams')->everyFiveMinutes()->skip(function () {
            return date('N') == 5 && date("H:i")=="09:00";  //skip on friday 9:00
        });
        $schedule->command('stat:calculate_top')->fridays()->dailyAt('09:00');
    }

    /**
     * Register the commands for the application.
     *r
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}