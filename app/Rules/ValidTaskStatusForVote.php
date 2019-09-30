<?php

namespace App\Rules;

use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\Rule;

class ValidTaskStatusForVote implements Rule
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
        if($this->task->status!=TaskStatus::AllowVote && $this->task->status!=TaskStatus::IntervalFinishedAllowVote)
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
        return trans('api/task.vote_finished');
    }
}