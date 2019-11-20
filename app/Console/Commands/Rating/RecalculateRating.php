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

        $friday = Carbon::parse('previous friday')->subMonths(2);
        $prevHistory = [];

        do{

            $prevDay = $friday->subDays(2);
            $nextDay = $friday->addDay();

            $history = ChannelHistory::where('updated_at', '>', $prevDay)
                            ->where('updated_at', '<', $nextDay);

            $count = $history->count();

            if($count>0 && count($prevHistory)>0)
            {
                foreach($history->get() as $data)
                {
                    if(isset($prevHistory[$data->channel_id]))
                    {
                        $rating = $data->views - $prevHistory[$data->channel_id];
                        $data->channel->update(['rating' => $rating]);
                    }

                    $prevHistory[$data->channel_id] = $data->views;
                }
            }

            $friday = $friday->addWeek();

        } while($count>0);

        $bar->finish();
    }
}