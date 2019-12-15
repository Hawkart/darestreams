<?php

namespace App\Console\Commands\Ones;

use App\Models\Rating\ChannelHistory;
use App\Models\Rating\GameChannelHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BugfixRatingGameChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bugfix:rating-game-channel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $bar = $this->output->createProgressBar(100);
        $prevDay = Carbon::now('UTC')->subDays(5);
        $prevWeek = Carbon::now('UTC')->subDays(10);

        GameChannelHistory::where('created_at', '>', $prevDay)->delete();
        ChannelHistory::where('created_at', '>', $prevDay)->delete();

        $histories = ChannelHistory::where('created_at', '>', $prevWeek)->with(['channel'])->get();
        foreach($histories as $history)
        {
            $history->channel->update([
                'followers' => $history->followers,
                'views' => $history->views,
                'rating' => $history->rating
            ]);
        }

        $bar->finish();
    }
}