<?php

namespace App\Rules;

use App\Enums\StreamStatus;
use App\Models\Stream;
use Illuminate\Contracts\Validation\Rule;

class AllowsChangeStreamStatus implements Rule
{
    public $stream;

    /**
     * AllowsChangeStreamStatusFromActiveToFinishedWaitPay constructor.
     * @param $stream_id
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
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
        if($value>-1 && $value!=$this->stream->status && $value!=StreamStatus::FinishedWaitPay && $value!=StreamStatus::Canceled)
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('api/stream.cannot_update_status_stream');
    }
}
