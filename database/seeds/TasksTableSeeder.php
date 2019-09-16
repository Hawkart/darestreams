<?php

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Stream;

class TasksTableSeeder extends Seeder
{
    public function run()
    {
        $streams = Stream::all();
        foreach($streams as $stream)
        {
            $count = rand(5, 10);
            for($i = 1; $i<=$count; $i++)
            {
                factory(Task::class)->create([
                    'stream_id' => $stream->id,
                    'small_desc' => "Тестовое задание №".$i,
                    'full_desc'  => "Полное описание тестового задания №".$i,
                ]);
            }
        }
    }
}