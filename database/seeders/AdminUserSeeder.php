<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'name'         => 'Administrador',
            'cpf'          => '12345678900',
            'email'        => 'admin@example.com',
            'password'     => Hash::make('password'),
            'job_position' => 'Administrador de Sistema',
            'birth_date'   => '1990-01-01',
            'zip_code'     => '13000-000',
            'street'       => 'Rua Exemplo',
            'number'       => '123',
            'complement'   => 'Sala 45',
            'neighborhood' => 'Centro',
            'city'         => 'Campinas',
            'state'        => 'SP',
            'role'         => UserRole::ADMIN->value,
            'manager_id'   => null,
        ]);
    }
}
