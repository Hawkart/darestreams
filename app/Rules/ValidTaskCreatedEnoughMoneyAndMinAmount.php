<?php

namespace App\Rules;

use App\Models\Stream;
use Illuminate\Contracts\Validation\Rule;

class ValidTaskCreatedEnoughMoneyAndMinAmount implements Rule
{
    public $stream_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($stream_id)
    {
        $this->stream_id = $stream_id;
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
        $stream = Stream::findOrFail($this->stream_id);
        $min_amount = $stream->getTaskCreateAmount();

        if(!$user->ownerOfChannel($stream->channel_id))
        {
            if($user->account->amount<$value)
                abort(response()->json(['message' => trans('api/task.not_enough_money')], 402));

            if($value<$min_amount) return false;
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
        return trans('api/task.not_enough_money');
    }
}