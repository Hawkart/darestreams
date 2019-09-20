<?php

namespace App\Rules;

use App\Enums\StreamStatus;
use Illuminate\Contracts\Validation\Rule;

class ValidChannelDontHaveActiveStreams implements Rule
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
        $user = auth()->user();

        //Cannot create new before exists not finished
        if($user->streams()->whereIn('status', [StreamStatus::Created, StreamStatus::Active])->count()>0)
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
        return trans('api/stream.you_still_have_active_streams');
    }
}
