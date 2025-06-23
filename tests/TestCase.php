<?php

declare(strict_types = 1);

namespace Tests;

use App\Rules\ValidCpf;
use App\Services\Partners\ViaCep\ViaCepService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Rules\TestingValidCpf;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configura o banco de dados para SQLite em memória
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
        
        // Configura o ViaCepService para modo offline durante os testes
        config(['address.offline_mode' => true]);
        
        // Mock do ViaCepService para retornar true para qualquer CEP
        $this->app->instance(ViaCepService::class, new class extends ViaCepService {
            public function __construct()
            {
                parent::__construct(true, true);
            }
            
            public function validateZipCode(string $zipCode): bool
            {
                return true;
            }
        });
        
        // Registrar a regra de validação de CPF de teste
        $this->app->singleton(ValidCpf::class, function () {
            return new TestingValidCpf();
        });
    }
}
