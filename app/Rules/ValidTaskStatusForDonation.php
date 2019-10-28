<?php

namespace App\Rules;

use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\Rule;

class ValidTaskStatusForDonation implements Rule
{
    public $task;
    public $message;

    /**
     * ValidAmountDonation constructor.
     * @param $task
     */
    public function __construct($task)
    {
        $this->task = $task;
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
        $user = auth()->user();

        if($this->task->status!=TaskStatus::Active && !($user->id==$this->task->user_id && $this->task->status==TaskStatus::Created))
        {
            $this->message = trans('api/task.failed_not_active');
            return false;
        }


        //check fake rules
        if($this->task->stream->user->fake && !$user->fake)
        {
            $this->message = trans('api/task.failed_real_user_on_fake_stream');
            return false;
        }
        if(!$this->task->stream->user->fake && $user->fake)
        {
            $this->message = trans('api/task.failed_fake_user_on_real_stream');
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