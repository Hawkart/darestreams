<?php

namespace App\Console\Commands;

use App\Models\Rating\Channel as StatChannel;
use App\Models\Channel;
use Illuminate\Console\Command;

class SyncStatChannels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:channels_sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync channels with stat.';

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

        $channels = StatChannel::where('exist', 1)->get();
        foreach($channels as $stat)
        {
            $channel = Channel::where('exid', $stat->exid)->first();

            if($channel)
                $stat->update(['channel_id' => $channel->id]);
            else
                $stat->update(['exist' => 0]);
        }

        $bar->finish();
    }
}