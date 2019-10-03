<?php

namespace App\Rules;

use App\Enums\StreamStatus;
use App\Models\Stream;
use Illuminate\Contracts\Validation\Rule;

class ValidCanTaskCreate implements Rule
{
    public $stream_id;
    public $message;

    /**
     * ValidCanTaskCreate constructor.
     * @param $stream_id
     */
    public function __construct($stream_id)
    {
        $this->stream_id = $stream_id;
        $this->message = '';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = auth()->user;

        $stream = Stream::findOrFail($this->stream_id);

        if($stream->checkAlreadyFinished())
        {
            $this->message = trans('api/stream.stream_finished');
            return false;
        }

        if($stream->status==StreamStatus::Active && !$stream->allow_task_when_stream)
        {
            $this->message = trans('api/stream.not_allow_create_task_when_stream_active');
            return false;
        }
        else if($stream->status==StreamStatus::Created && !$stream->allow_task_before_stream)
        {
            $this->message = trans('api/stream.not_allow_create_task_when_stream_before');
            return false;
        }

        //check fake rules
        if($stream->user->fake && !$user->fake)
        {
            $this->message = 'Real user cannot create task for fake stream';
            return false;
        }
        if(!$stream->user->fake && $user->fake)
        {
            $this->message = 'Fake user cannot create task for real stream';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}