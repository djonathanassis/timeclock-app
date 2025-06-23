<?php

declare(strict_types = 1);

namespace App\Services\Partners\ViaCep;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ViaCepService
{
    private const string BASE_URL = 'https://viacep.com.br/ws/';

    private const int CACHE_TTL = 2592000;

    public function __construct(
        private readonly bool $offlineMode = false,
        private readonly bool $acceptValidFormatOnFailure = true
    ) {
    }

    public function validateZipCode(string $zipCode): bool
    {
        if (! $this->isValidFormat($zipCode)) {
            return false;
        }

        if ($this->offlineMode) {
            return true;
        }

        $cacheKey = "valid_zipcode_{$zipCode}";

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL), function () use ($zipCode): bool {
            try {
                $response = $this->getHttpClient()
                    ->get(self::BASE_URL . "{$zipCode}/json/");

                return $response->successful() && ! isset($response->json()['erro']);
            } catch (\Throwable $throwable) {
                Log::warning("Erro ao validar CEP: " . $throwable->getMessage());

                return $this->acceptValidFormatOnFailure;
            }
        });
    }

    private function isValidFormat(string $zipCode): bool
    {
        return preg_match('/^\d{8}$/', $zipCode) === 1;
    }

    private function getHttpClient(): PendingRequest
    {
        return Http::timeout(5)
            ->retry(2, 300)
            ->acceptJson();
    }
}
