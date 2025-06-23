<?php

declare(strict_types = 1);

namespace Tests\Feature\TimeEntry;

use App\Enums\UserRole;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_register_time_entry(): void
    {
        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        $response = $this->actingAs($employee)->post(route('time-entries.store'));

        $response->assertRedirect(route('time-entries.index'));
        $response->assertSessionHas('status', 'Ponto registrado com sucesso!');
        $this->assertDatabaseHas('time_entries', [
            'user_id' => $employee->id,
        ]);
    }

    public function test_admin_can_register_time_entry(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->actingAs($admin)->post(route('time-entries.store'));

        $response->assertRedirect(route('time-entries.index'));
        $response->assertSessionHas('status', 'Ponto registrado com sucesso!');
        $this->assertDatabaseHas('time_entries', [
            'user_id' => $admin->id,
        ]);
    }

    public function test_employee_can_view_own_time_entries(): void
    {
        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        // Cria alguns registros de ponto para o funcionário
        TimeEntry::factory()->count(3)->create([
            'user_id' => $employee->id,
        ]);

        $response = $this->actingAs($employee)->get(route('time-entries.index'));

        $response->assertStatus(200);
        $response->assertViewHas('timeEntries');
        $response->assertSee($employee->name);
    }

    public function test_admin_can_view_time_entry_report(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->actingAs($admin)->get(route('time-entries.report'));

        $response->assertStatus(200);
    }

    public function test_employee_cannot_view_time_entry_report(): void
    {
        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        $response = $this->actingAs($employee)->get(route('time-entries.report'));

        $response->assertStatus(403);
    }

    public function test_prevent_multiple_time_entries_in_short_interval(): void
    {
        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        // Primeiro registro de ponto
        $this->actingAs($employee)->post(route('time-entries.store'));

        // Tenta registrar novo ponto rapidamente
        $response = $this->actingAs($employee)->post(route('time-entries.store'));

        $response->assertRedirect(route('time-entries.index'));
        $response->assertSessionHas('error');
    }
} 