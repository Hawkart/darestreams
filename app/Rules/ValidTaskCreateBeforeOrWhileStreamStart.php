<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTaskCreateBeforeOrWhileStreamStart implements Rule
{
    public $checkbox_before;

    public $checkbox_while;

    public function __construct($checkbox_before, $checkbox_while)
    {
        $this->checkbox_before = $checkbox_before;
        $this->checkbox_while = $checkbox_while;
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
        if (!$this->checkbox_before && !$this->checkbox_while) {
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
