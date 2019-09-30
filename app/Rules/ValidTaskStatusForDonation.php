<?php

namespace App\Rules;

use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\Rule;

class ValidTaskStatusForDonation implements Rule
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
        $user = auth()->user();

        if($this->task->status!=TaskStatus::Active && !($user->id==$this->task->user_id && $this->task->status==TaskStatus::Created))
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
        return trans('api/task.failed_not_active');
    }
}