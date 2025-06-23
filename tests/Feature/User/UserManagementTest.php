<?php

declare(strict_types = 1);

namespace Tests\Feature\User;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configura o ViaCepService para modo offline durante os testes
        config(['address.offline_mode' => true]);
    }

    /**
     * Gera um CPF válido para testes
     */
    private function generateValidCPF(): string
    {
        // Gera os 9 primeiros dígitos
        $cpf = [];
        for ($i = 0; $i < 9; $i++) {
            $cpf[$i] = random_int(0, 9);
        }
        
        // Calcula o primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $cpf[9] = ($resto < 2) ? 0 : 11 - $resto;
        
        // Calcula o segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $cpf[10] = ($resto < 2) ? 0 : 11 - $resto;
        
        // Formata o CPF como string
        return implode('', $cpf);
    }

    public function test_admin_can_view_user_listing(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
            'manager_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('employees');
        $response->assertSee($employee->name);
    }

    public function test_admin_can_create_new_user(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->actingAs($admin)->get(route('users.create'));
        $response->assertStatus(200);

        // Usar dados válidos em vez de mocks
        $validCpf = $this->generateValidCPF();
        $validZipCode = '01001000'; // CEP real e válido (Sé, São Paulo)

        $userData = [
            'name' => 'Novo Funcionário',
            'cpf' => $validCpf,
            'email' => 'novo@exemplo.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'job_position' => 'developer',
            'role' => 'employee',
            'birth_date' => '1990-01-01',
            'zip_code' => $validZipCode,
            'street' => 'Praça da Sé',
            'number' => '123',
            'complement' => 'Apto 101',
            'neighborhood' => 'Sé',
            'city' => 'São Paulo',
            'state' => 'SP',
            'manager_id' => $admin->id,
        ];

        $storeResponse = $this->actingAs($admin)->post(route('users.store'), $userData);
        $storeResponse->assertRedirect(route('users.index'));
        $storeResponse->assertSessionHas('status', 'Funcionário cadastrado com sucesso!');

        $this->assertDatabaseHas('users', [
            'name' => 'Novo Funcionário',
            'email' => 'novo@exemplo.com',
        ]);
    }

    public function test_admin_can_edit_user(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);
        
        // Criar um usuário com CPF válido para editar
        $validCpf = $this->generateValidCPF();
        $validZipCode = '01001000'; // CEP real e válido (Sé, São Paulo)
        
        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
            'manager_id' => $admin->id,
            'name' => 'Nome Original',
            'cpf' => $validCpf,
            'zip_code' => $validZipCode,
            'street' => 'Praça da Sé',
            'neighborhood' => 'Sé',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $response = $this->actingAs($admin)->get(route('users.edit', $employee));
        $response->assertStatus(200);

        $updateData = [
            'name' => 'Nome Atualizado',
            'cpf' => $validCpf, // Mantém o mesmo CPF válido
            'email' => $employee->email,
            'job_position' => $employee->job_position,
            'role' => 'employee',
            'birth_date' => $employee->birth_date->format('Y-m-d'),
            'zip_code' => $validZipCode, // Mantém o mesmo CEP válido
            'street' => 'Praça da Sé',
            'number' => $employee->number ?? '100',
            'complement' => $employee->complement ?? 'Sala 202',
            'neighborhood' => 'Sé',
            'city' => 'São Paulo',
            'state' => 'SP',
            'manager_id' => $admin->id,
        ];

        $updateResponse = $this->actingAs($admin)
            ->put(route('users.update', $employee), $updateData);
        
        $updateResponse->assertRedirect(route('users.index'));
        $updateResponse->assertSessionHas('status', 'Funcionário atualizado com sucesso!');

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'name' => 'Nome Atualizado',
        ]);
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
            'manager_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('users.destroy', $employee));
        
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('status', 'Funcionário excluído com sucesso!');

        $this->assertSoftDeleted('users', [
            'id' => $employee->id,
        ]);
    }

    public function test_admin_can_view_user_details(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
            'manager_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('users.show', $employee));
        
        $response->assertStatus(200);
        $response->assertViewHas('employee');
        $response->assertSee($employee->name);
        $response->assertSee($employee->email);
    }

    public function test_employee_cannot_access_user_crud(): void
    {
        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        $this->actingAs($employee)->get(route('users.index'))->assertStatus(403);
        $this->actingAs($employee)->get(route('users.create'))->assertStatus(403);
        $this->actingAs($employee)->get(route('users.edit', $employee))->assertStatus(403);
        $this->actingAs($employee)->get(route('users.show', $employee))->assertStatus(403);
    }
} 