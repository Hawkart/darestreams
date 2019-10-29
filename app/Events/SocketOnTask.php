<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Log;

class SocketOnTask implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
        Log::info('Socket on task', [
            'data' => $this->data,
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        $user_id = $this->data->stream->channel->user_id;

        return new PrivateChannel('users.'.$user_id);
    }
}
