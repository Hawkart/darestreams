<?php

namespace App\Console\Commands\Rating;

use App\Models\Rating\ChannelHistory;
use App\Models\Rating\Channel;
use App\Models\Channel as Ch;
use App\Acme\Helpers\TwitchHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecalculateRating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get views for active streams.';

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

        echo Carbon::parse('previous friday')."\r\n";

        $friday = Carbon::parse('previous friday')->subWeeks(8);
        $prevHistory = [];

        echo $friday."\r\n";

        do{
            $prevDay = Carbon::parse($friday)->subDays(2);
            $nextDay = Carbon::parse($friday)->addDay();

            $history = ChannelHistory::where('created_at', '>', $prevDay)
                            ->where('created_at', '<', $nextDay);

            $count = $history->count();

            if($count>0 && count($prevHistory)>0)
            {
                foreach($history->get() as $data)
                {
                    if(isset($prevHistory[$data->channel_id]))
                    {
                        $rating = $data->views - $prevHistory[$data->channel_id];

                        if($rating<0)
                            $rating = 0;

                        $data->channel->update(['rating' => $rating]);

                        echo "change rating = ".$rating."\r\n";
                    }

                    $prevHistory[$data->channel_id] = $data->views;
                }
            }

            $friday = Carbon::parse($friday)->addWeek();

            echo $friday."\r\n";

        } while(Carbon::parse($friday)->lte(Carbon::now('UTC')));

        $bar->finish();
    }
}