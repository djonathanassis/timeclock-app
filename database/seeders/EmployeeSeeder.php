<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Enums\JobPosition;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@example.com')->first();

        if (! $admin) {
            $this->command->error(
                'Usuário administrador não encontrado! Execute AdminUserSeeder primeiro.'
            );

            return;
        }

        $employees = [
            [
                'name'         => 'João Silva',
                'cpf'          => '98765432100',
                'email'        => 'joao@example.com',
                'job_position' => JobPosition::DEVELOPER->value,
                'birth_date'   => '1992-05-15',
            ],
            [
                'name'         => 'Maria Oliveira',
                'cpf'          => '45678912300',
                'email'        => 'maria@example.com',
                'job_position' => JobPosition::ANALYST->value,
                'birth_date'   => '1988-10-22',
            ],
            [
                'name'         => 'Pedro Santos',
                'cpf'          => '78912345600',
                'email'        => 'pedro@example.com',
                'job_position' => JobPosition::DEVELOPER->value,
                'birth_date'   => '1995-03-30',
            ],
        ];

        foreach ($employees as $employeeData) {
            User::query()->create([
                'name'         => $employeeData['name'],
                'cpf'          => $employeeData['cpf'],
                'email'        => $employeeData['email'],
                'password'     => Hash::make('password'),
                'job_position' => $employeeData['job_position'],
                'birth_date'   => $employeeData['birth_date'],
                'zip_code'     => '13100-000',
                'street'       => 'Avenida Exemplo',
                'number'       => '456',
                'complement'   => null,
                'neighborhood' => 'Jardim Exemplo',
                'city'         => 'Campinas',
                'state'        => 'SP',
                'role'         => UserRole::EMPLOYEE->value,
                'manager_id'   => $admin->id,
            ]);
        }

        //        User::factory()
        //            ->count(5)
        //            ->create([
        //                'role'       => UserRole::EMPLOYEE->value,
        //                'manager_id' => $admin->id,
        //            ]);
    }
}
