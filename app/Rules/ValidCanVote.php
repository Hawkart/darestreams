<?php

namespace App\Rules;

use App\Enums\TaskStatus;
use App\Enums\VoteStatus;
use Illuminate\Contracts\Validation\Rule;

class ValidCanVote implements Rule
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

        $votes = $this->task->votes()->where('user_id', $user->id);

        if($votes->count()==0)
        {
            $this->message = trans('api/task.cannot_vote');
            return false;
        }else{

            if($votes->first()->vote!=VoteStatus::Pending)
            {
                $this->message = trans('api/task.already_vote');
                return false;
            }
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