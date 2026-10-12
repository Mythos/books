<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Nicebooks\Isbn\Exception\InvalidIsbnException;
use Nicebooks\Isbn\Isbn as IsbnNumber;

class Isbn implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            IsbnNumber::of((string) $value);
        } catch (InvalidIsbnException) {
            $fail(__('The provided ISBN is invalid'));
        }
    }
}
