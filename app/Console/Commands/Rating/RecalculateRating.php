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

        $friday = Carbon::parse('previous friday')->subWeeks(8);
        $prevHistory = [];

        do{
            $prevDay = Carbon::parse($friday)->subDays(2);
            $nextDay = Carbon::parse($friday)->addDay();

            $history = ChannelHistory::whereDate('created_at', '>', $prevDay)
                ->whereDate('created_at', '<', $nextDay);

            if($history->count()>0)
            {
                foreach($history->get() as $data)
                {
                    if(isset($prevHistory[$data->channel_id]))
                    {
                        $rating = ceil(($data->views - $prevHistory[$data->channel_id])/1000);

                        if($rating<0)
                            $rating = 0;

                        $data->update(['rating' => $rating]);
                        $data->channel->update(['rating' => $rating]);

                        echo "change rating = ".$rating."\r\n";
                    }

                    $prevHistory[$data->channel_id] = $data->views;
                }

                $place = 1;
                foreach($history->orderBy('rating', 'DESC')->get() as $h)
                {
                    $h->update(['place' => $place]);
                    $place++;
                }
            }

            $friday = Carbon::parse($friday)->addWeek();

            echo $friday."\r\n";

        } while(Carbon::parse($friday)->lte(Carbon::now('UTC')));

        $bar->finish();
    }
}