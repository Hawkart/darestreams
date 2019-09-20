<?php

namespace App\Rules;

use App\Enums\StreamStatus;
use Illuminate\Contracts\Validation\Rule;

class AllowsChangeStreamStatusFromActiveToFinishedWaitPay implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        //try to change to another status
        if($value>-1 && $value!=StreamStatus::FinishedWaitPay)
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
