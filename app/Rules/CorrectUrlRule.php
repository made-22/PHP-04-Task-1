<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Rule for checking url existing and correct working
 */
class CorrectUrlRule implements Rule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            return !Http::head($value)->failed();
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return __('validation.link');
    }
}
