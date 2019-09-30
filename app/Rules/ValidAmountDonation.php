<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidAmountDonation implements Rule
{
    public $task;

    /**
     * ValidAmountDonation constructor.
     * @param $task
     */
    public function __construct($task)
    {
        $this->task = $task;
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
        if($value<$this->task->min_donation)
            return false;

        if($value > auth()->user()->account->amount)
            abort(response()->json(['message' => trans('api/transaction.not_enough_money')], 402));

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