<?php

namespace App\Console\Commands;

use App\Models\Stream;
use Illuminate\Console\Command;

class UpdateStreamTasksDesc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:update_desc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update desc of tasks';

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

        $streams = Stream::with(['tasks'])->get();
        if(count($streams)>0)
        {
            foreach($streams as $stream)
            {
                $tasks = $stream->tasks;

                if(count($tasks)>0)
                {
                    $key = 1;
                    foreach($tasks as $task)
                    {
                        $task->update([
                            'small_desc' => "Тестовое задание №".$key,
                            'full_desc'  => "Полное описание тестового задания №".$key,
                        ]);
                        $key++;
                    }
                }
            }
        }

        $bar->finish();
    }
}