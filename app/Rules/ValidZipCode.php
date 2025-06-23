<?php

declare(strict_types = 1);

namespace App\Rules;

use App\Services\Partners\ViaCep\ViaCepService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class ValidZipCode implements ValidationRule
{
    public function __construct(
        private readonly ViaCepService $addressService = new ViaCepService()
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $stringValue = is_null($value) ? '' : (string) $value;
        $zipCode     = preg_replace('/\D/', '', $stringValue) ?? '';

        try {
            if ($zipCode === '' || ! $this->addressService->validateZipCode($zipCode)) {
                $fail('O :attribute informado não é um CEP válido.');
            }
        } catch (\Exception $e) {
            Log::warning("Erro ao validar CEP: {$e->getMessage()}");

            if (in_array(preg_match('/^\d{8}$/', $zipCode), [0, false], true)) {
                $fail('O :attribute informado não é um CEP válido.');
            }
        }
    }
}
