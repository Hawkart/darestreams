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
        $channel_id = $stream->channel_id;
        $filled_amount = $value;

        //If it's streamer
        if($user->ownerOfChannel($channel_id))
            $filled_amount = 0;

        //If not owner of stream check how much money you have
        if(!$user->ownerOfChannel($channel_id) && $user->account->amount<$min_amount)
            abort(response()->json(['message' => trans('api/task.not_enough_money')], 402));

        if($filled_amount<$min_amount && !$user->ownerOfChannel($channel_id))
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
        return trans('api/task.not_enough_money');
    }
}
