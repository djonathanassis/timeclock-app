<?php

declare(strict_types = 1);

namespace Tests\Feature\Middleware;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckUserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->actingAs($admin)->get(route('users.index'));
        
        $response->assertStatus(200);
    }

    public function test_employee_cannot_access_admin_routes(): void
    {
        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        $response = $this->actingAs($employee)->get(route('users.index'));
        
        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('users.index'));
        
        $response->assertRedirect(route('login'));
    }

    public function test_both_roles_can_access_common_routes(): void
    {
        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);
        
        $response = $this->actingAs($employee)->get(route('dashboard'));
        $response->assertStatus(200);
        
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);
        
        $response = $this->actingAs($admin)->get(route('dashboard'));
        $response->assertStatus(200);
    }
} 