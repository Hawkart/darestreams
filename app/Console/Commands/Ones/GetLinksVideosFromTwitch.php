<?php

namespace App\Console\Commands\Ones;

use App\Acme\Helpers\TwitchHelper;
use App\Enums\StreamStatus;
use App\Models\Channel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetLinksVideosFromTwitch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channel:twitch_videos_links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get channel's videos from twitch.";

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

        $channels = Channel::whereHas('user', function($q){
               $q->where('role_id', '<>', 1);
            })
            ->whereHas('streams', function($q){
                $q->whereIn('status', [StreamStatus::FinishedWaitPay, StreamStatus::FinishedIsPayed]);
            }, '>', 0)
            ->with('streams')
            ->get();

        if(count($channels)>0)
        {
            foreach($channels as $channel)
            {
                $streams = $channel->streams;

                if(count($streams)>0)
                {
                    $videos = [];
                    $twitch = new TwitchHelper();
                    $data = $twitch->getChannelVideos($channel->exid, 25, 0, 'archive');

                    if(!empty($data) && isset($data['videos']) && $data['_total']>0)
                    {
                        foreach($data['videos'] as $video)
                        {
                            $videos[] = [
                                'id' => $video['_id'],
                                'created_at' => $video['created_at']
                            ];
                        }
                    }

                    foreach($streams as $stream)
                    {
                        if(!in_array($stream->status, [StreamStatus::FinishedWaitPay, StreamStatus::FinishedIsPayed]))
                            continue;

                        foreach($videos as $video)
                        {
                            $start_at = Carbon::parse($video['created_at']);
                            $minutes = ceil($stream->start_at->diffInMinutes($start_at));

                            if($minutes<90)
                            {
                                $stream->update([
                                   'link' =>  $video['id']
                                ]);

                                echo $video['id']."\r\n";

                                break;
                            }
                        }
                    }
                }
            }
        }

        $bar->finish();
    }
}