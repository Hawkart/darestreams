<?php

namespace App\Console\Commands;

use App\Enums\StreamStatus;
use App\Models\Stream;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateStreamsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streams:update_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set stream to active if it\'s time';

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
        $now = Carbon::now('UTC');

        try {
            DB::transaction(function () use ($now) {
                Stream::where('status', StreamStatus::Created)->where('start_at', '>', $now)
                        ->update(['status' => StreamStatus::Active]);
            });
        } catch (\Exception $e) {
            echo response($e->getMessage(), 422);
        }

        /*$streams = Stream::where('status', StreamStatus::Created)->where('start_at', '>', $now)->get();

        if(count($streams)>0)
        {
            foreach($streams as $stream)
            {
                try {
                    DB::transaction(function () use ($stream) {
                        $stream->update(['status' => StreamStatus::Active]);
                    });
                } catch (\Exception $e) {
                    echo response($e->getMessage(), 422);
                }
            }
        }*/

        $bar->finish();
    }
}
