<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\TimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Random\RandomException;

class TimeEntrySeeder extends Seeder
{
    /**
     * @throws RandomException
     */
    public function run(): void
    {
        $employees = User::query()->where('role', UserRole::EMPLOYEE->value)->get();

        if ($employees->isEmpty()) {
            $this->command->error(
                'Nenhum funcionário encontrado! Execute EmployeeSeeder primeiro.'
            );

            return;
        }

        foreach ($employees as $employee) {
            $this->createTimeEntriesForEmployee($employee);
        }
    }

    /**
     * Cria registros de ponto para um funcionário específico.
     * @throws RandomException
     */
    private function createTimeEntriesForEmployee(User $employee): void
    {
        // Obtém os últimos 5 dias úteis (excluindo sábados e domingos)
        $workDays = $this->getLastWorkDays(5);

        foreach ($workDays as $day) {
            // Entrada pela manhã (entre 8:00 e 8:30)
            $morningEntry = (clone $day)->addHours(8)->addMinutes(random_int(0, 30));
            TimeEntry::query()->create([
                'user_id'     => $employee->id,
                'recorded_at' => $morningEntry,
            ]);

            // Saída para almoço (entre 12:00 e 12:30)
            $lunchExit = (clone $day)->addHours(12)->addMinutes(random_int(0, 30));
            TimeEntry::query()->create([
                'user_id'     => $employee->id,
                'recorded_at' => $lunchExit,
            ]);

            // Retorno do almoço (entre 13:00 e 13:30)
            $lunchReturn = (clone $day)->addHours(13)->addMinutes(random_int(0, 30));
            TimeEntry::query()->create([
                'user_id'     => $employee->id,
                'recorded_at' => $lunchReturn,
            ]);

            // Saída no fim do dia (entre 17:00 e 18:00)
            $eveningExit = (clone $day)->addHours(17)->addMinutes(random_int(0, 60));
            TimeEntry::query()->create([
                'user_id'     => $employee->id,
                'recorded_at' => $eveningExit,
            ]);
        }
    }

    /**
     * Obtém os últimos N dias úteis (excluindo sábados e domingos).
     *
     * @return array<Carbon>
     */
    private function getLastWorkDays(int $count): array
    {
        $workDays = [];
        $date     = Carbon::today();

        while (count($workDays) < $count) {
            $date = $date->subDay();

            if ($date->dayOfWeek !== 0 && $date->dayOfWeek !== 6) {
                $workDays[] = clone $date;
            }
        }

        return $workDays;
    }
}
