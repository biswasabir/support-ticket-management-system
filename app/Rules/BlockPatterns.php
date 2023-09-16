<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BlockPatterns implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $strippedValue = strip_tags($value);
        if ($strippedValue !== $value) {
            return false;
        }
        if (preg_match('/\{\{[^}]+\}\}|{!![^}]+!!}|<\?php/', $value)) {
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
        return __('validation.block_patterns');
    }
}