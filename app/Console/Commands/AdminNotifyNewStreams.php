<?php

namespace App\Console\Commands;

use App\Acme\Helpers\TwitchHelper;
use App\Enums\StreamStatus;
use App\Models\Stream;
use App\Models\User;
use App\Notifications\NotifyFollowersAboutStream;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AdminNotifyNewStreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streams:notify_admins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify admins about new streams.';

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

        $users = User::where('fake', '<>', 1)
                    ->has('channel', '>', 0)
                    ->with('channel')
                    ->get();

        $admin = User::admins()->first();

        if(count($users)>0 && $admin)
        {
            foreach($users as $user)
            {
                $channel = $user->channel;

                $twitch = new TwitchHelper();

                if($channel && $data = $twitch->getStreamByUser($channel->exid) && isset($data['stream']))
                {
                    $now = Carbon::now('UTC');
                    $start_at = Carbon::parse($data['stream']['created_at']);

                    $minutes = ceil($now->diffInSeconds($start_at)/60);

                    if($minutes<5)
                    {
                        $details = [
                            'greeting' => 'Hi '.$admin->name,
                            'body' => 'The stream of '.$user->nickname." started",
                            'actionText' => 'View stream',
                            'actionURL' => 'https://twitch.tv/'.$user->nickname,
                            'subject' => 'New stream of '.$user->nickname
                        ];

                        $when = now()->addSeconds(30);
                        $admin->notify((new NotifyFollowersAboutStream($details))->delay($when));
                    }
                }

                sleep(2);
            }
        }

        $bar->finish();
    }
}