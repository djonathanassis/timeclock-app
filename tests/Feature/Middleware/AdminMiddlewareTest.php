<?php

namespace Tests\Feature\Middleware;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pode_acessar_rotas_protegidas(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->actingAs($admin)->get(route('users.index'));
        $response->assertStatus(200);
    }

    public function test_usuario_comum_nao_pode_acessar_rotas_protegidas(): void
    {
        $usuario = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        $response = $this->actingAs($usuario)->get(route('users.index'));
        $response->assertStatus(403);
    }

    public function test_usuario_nao_autenticado_redirecionado_para_login(): void
    {
        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('login'));
    }
}
