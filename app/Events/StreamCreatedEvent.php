<?php

namespace App\Events;

use App\Models\Stream;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class StreamCreatedEvent
{
    use Dispatchable, SerializesModels;

    public $stream;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }
}
