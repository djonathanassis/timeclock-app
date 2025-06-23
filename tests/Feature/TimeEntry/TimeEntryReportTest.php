<?php

declare(strict_types = 1);

namespace Tests\Feature\TimeEntry;

use App\Enums\UserRole;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TimeEntryReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_time_entry_report(): void
    {
        // Configuração
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'name' => 'Gestor Admin',
        ]);

        $employees = User::factory()->count(3)->create([
            'role'       => UserRole::EMPLOYEE,
            'manager_id' => $admin->id,
        ]);

        foreach ($employees as $employee) {
            TimeEntry::factory()->count(5)->create([
                'user_id' => $employee->id,
            ]);
        }

        // Teste
        $response = $this->actingAs($admin)
            ->get(route('time-entries.report'));

        $response->assertStatus(200);
        $response->assertViewHas('entries');
    }

    public function test_admin_can_filter_report_by_date_range(): void
    {
        // Configuração
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $employees = User::factory()->count(3)->create([
            'role'       => UserRole::EMPLOYEE,
            'manager_id' => $admin->id,
        ]);

        foreach ($employees as $employee) {
            TimeEntry::factory()->count(5)->create([
                'user_id' => $employee->id,
            ]);
        }

        // Teste
        $startDate = now()->subDays(7)->format('Y-m-d');
        $endDate   = now()->format('Y-m-d');

        $response = $this->actingAs($admin)
            ->get(route('time-entries.report', [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('entries');
    }

    public function test_employee_cannot_view_time_entry_report(): void
    {
        // Configuração
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $employee = User::factory()->create([
            'role'       => UserRole::EMPLOYEE,
            'manager_id' => $admin->id,
        ]);

        // Teste
        $response = $this->actingAs($employee)
            ->get(route('time-entries.report'));

        $response->assertStatus(403);
    }

    public function test_report_contains_required_fields(): void
    {
        // Configuração
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $employees = User::factory()->count(3)->create([
            'role'       => UserRole::EMPLOYEE,
            'manager_id' => $admin->id,
        ]);

        foreach ($employees as $employee) {
            TimeEntry::factory()->count(5)->create([
                'user_id' => $employee->id,
            ]);
        }

        // Teste
        $response = $this->actingAs($admin)
            ->get(route('time-entries.report'));

        $response->assertStatus(200);
        $response->assertViewHas('entries');
    }
}
