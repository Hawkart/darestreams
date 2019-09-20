<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTaskCreateBeforeOrWhileStreamStart implements Rule
{
    public $inputs;

    public function __construct($inputs)
    {
        $this->inputs = $inputs;
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
        $allow_create_task_before = isset($this->inputs['allow_task_before_stream']) ? $this->inputs['allow_task_before_stream'] : null;
        $allow_create_task_while = isset($this->inputs['allow_task_when_stream']) ? $this->inputs['allow_task_when_stream'] : null;

        if (!$allow_create_task_before && !$allow_create_task_while) {
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
        return 'Some of the checkboxes have to be checked!';
    }
}
