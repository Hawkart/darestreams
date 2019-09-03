<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TaskUpdatedEvent
{
    use Dispatchable, SerializesModels;

    public $task;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}
