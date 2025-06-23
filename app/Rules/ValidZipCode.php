<?php

declare(strict_types = 1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ValidZipCode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $zipCode = preg_replace('/\D/', '', (string) $value);

        if (! $this->validFormat($zipCode) || ! $this->validateZipCode($zipCode)) {
            $fail('O :attribute informado não é um CEP válido.');
        }
    }

    private function validFormat(string $zipCode): bool
    {
        return strlen($zipCode) !== 8 || preg_match('/^(\d)\1{7}$/', $zipCode);
    }

    private function validateZipCode(string $cep): bool
    {
        try {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

            return $response->successful() && ! isset($response['erro']);
        } catch (\Exception) {
            return false;
        }
    }
}
