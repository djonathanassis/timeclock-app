<?php

declare(strict_types = 1);

namespace Tests\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TestingValidCpf implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Sempre valida para testes
    }
}
