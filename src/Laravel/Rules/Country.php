<?php

namespace GlobalCountries\Laravel\Rules;

use Closure;
use GlobalCountries\Countries;
use Illuminate\Contracts\Validation\ValidationRule;

class Country implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || !Countries::isValid($value)) {
            $fail("The selected :attribute is not a valid country code.");
        }
    }
}
