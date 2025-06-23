<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Enums\JobPosition;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Gera um CPF fictício para testes (não é um CPF válido)
        $cpf = '';

        for ($i = 0; $i < 11; $i++) {
            $cpf .= mt_rand(0, 9);
        }

        // Escolhe um cargo aleatório
        $jobPositions = [
            JobPosition::DEVELOPER->value,
            JobPosition::ANALYST->value,
            JobPosition::MANAGER->value,
            JobPosition::OTHER->value,
        ];

        return [
            'name'              => fake()->name(),
            'cpf'               => $cpf,
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'job_position'      => fake()->randomElement($jobPositions),
            'birth_date'        => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'zip_code'          => fake()->numerify('#####-###'),
            'street'            => fake()->streetName(),
            'number'            => fake()->buildingNumber(),
            'complement'        => fake()->optional(0.3)->secondaryAddress(),
            'neighborhood'      => fake()->word(),
            'city'              => fake()->city(),
            'state'             => fake()->randomElement(['SP', 'RJ', 'MG', 'RS', 'PR', 'SC', 'BA', 'PE']),
            'role'              => UserRole::EMPLOYEE->value, // Por padrão, cria funcionários
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Configure the model as an administrator.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::ADMIN->value,
        ]);
    }
}
