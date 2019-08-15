<?php

namespace App\Console\Commands;

use App\Http\Resources\ChannelResource;
use App\Models\Game;
use Illuminate\Console\Command;
use Image;

class ImportChannelsViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channels:get_views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get channels views.';

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

        $twitchClient = new \TwitchApi\TwitchApi([
            'client_id' => config('app.twitch_api_cid')
        ]);

        $twitchClient->setApiVersion(3);

        $channels = Channel::where('provider', 'twitch')->get();

        foreach($channels as $channel)
        {
            try{
                $data = $twitchClient->getChannel($channel->exid);
                $channel->update(['views' =>  $data['views']]);
            } catch (\Exception $e){
                //return $e->getMessage();
            }
        }

        $bar->finish();
    }
}
