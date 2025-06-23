<?php

declare(strict_types = 1);

namespace App\Services\Partners\ViaCep;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ViaCepService
{
    private const BASE_URL = 'https://viacep.com.br/ws/';

    private const CACHE_TTL = 2592000;

    /**
     * @param bool $offlineMode
     * @param bool $acceptValidFormatOnFailure
     */
    public function __construct(
        private readonly bool $offlineMode = false,
        private readonly bool $acceptValidFormatOnFailure = true
    ) {
    }

    /**
     * @param string $zipCode
     * @return bool
     */
    public function validateZipCode(string $zipCode): bool
    {
        if (!$this->isValidFormat($zipCode)) {
            return false;
        }

        if ($this->offlineMode) {
            return true;
        }

        $cacheKey = "valid_zipcode_{$zipCode}";

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL), function () use ($zipCode) {
            try {
                $response = $this->getHttpClient()
                    ->get(self::BASE_URL . "{$zipCode}/json/");

                return $response->successful() && !isset($response->json()['erro']);
            } catch (\Throwable $throwable) {
                Log::warning("Erro ao validar CEP: " . $throwable->getMessage());
                return $this->acceptValidFormatOnFailure;
            }
        });
    }

    /**
     * @param string $zipCode
     * @return bool
     */
    private function isValidFormat(string $zipCode): bool
    {
        return preg_match('/^\d{8}$/', $zipCode) === 1;
    }

    /**
     * @return PendingRequest
     */
    private function getHttpClient(): PendingRequest
    {
        return Http::timeout(5)
            ->retry(2, 300)
            ->acceptJson();
    }
}
