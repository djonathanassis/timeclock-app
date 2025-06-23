<?php

declare(strict_types = 1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/\D/', '', (string) $value);

        if ($this->validFormat($cpf) || ! $this->validDigits($cpf)) {
            $fail('O :attribute informado não é um CPF válido.');
        }
    }

    private function validFormat(string $cpf): bool
    {
        return strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf);
    }

    private function validDigits(string $cpf): bool
    {
        $sum       = $this->calculateDigitSum($cpf, 9, 10);
        $remainder = $this->calculateRemainder($sum);
        $digit1    = $this->calculateDigit($remainder);

        if ($digit1 !== (int) $cpf[9]) {
            return false;
        }

        $sum       = $this->calculateDigitSum($cpf, 10, 11);
        $remainder = $this->calculateRemainder($sum);
        $digit2    = $this->calculateDigit($remainder);

        return $digit2 === (int) $cpf[10];
    }

    private function calculateDigitSum(string $cpf, int $digits, int $position): int
    {
        $sum = 0;

        for ($i = 0; $i < $digits; $i++) {
            $sum += (int) $cpf[$i] * ($position - $i);
        }

        return $sum;
    }

    private function calculateRemainder(int $sum): int
    {
        return $sum % 11;
    }

    private function calculateDigit(int $remainder): int
    {
        return $remainder < 2 ? 0 : 11 - $remainder;
    }
}
