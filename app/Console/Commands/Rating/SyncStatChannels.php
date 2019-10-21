<?php

namespace App\Console\Commands\Rating;

use App\Models\Game;
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

        $channels = StatChannel::top()->get();
        foreach($channels as $stat)
        {
            $channel = Channel::where('exid', $stat->exid)
                            ->whereHas('user', function($q){
                                $q->where('fake', '<>', 1);
                            })->first();

            if($channel)
            {
                $stat->update([
                    'channel_id' => $channel->id,
                    'game_id' => $channel->game_id,
                ]);
            }else{
                $stat->update([
                    'channel_id' => 0,
                    'game_id' => $this->getGameIdByTitle($stat->json['game'])
                ]);
            }
        }

        $bar->finish();
    }

    /**
     * @param $title
     * @return int
     */
    protected function getGameIdByTitle($title)
    {
        $games = Game::where('title', '=', $title);
        if($games->count()>0)
            return $games->first()->id;

        return 0;
    }
}