<?php

declare(strict_types = 1);

namespace App\Repositories\TimeEntry;

use App\Models\TimeEntry;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TimeEntryRepository implements TimeEntryRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function getTimeRecordReport(
        ?Carbon $startDateTime = null,
        ?Carbon $endDateTime = null
    ): array {
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        
        // SQLite e MySQL têm funções diferentes para cálculo de idade
        $ageCalculation = $isSqlite
            ? "strftime('%Y', 'now') - strftime('%Y', u.birth_date) - (strftime('%m-%d', 'now') < strftime('%m-%d', u.birth_date))"
            : "TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE())";
        
        $query = "
            SELECT 
                te.id,
                u.name AS employee_name,
                u.job_position,
                {$ageCalculation} AS age,
                m.name AS manager_name,
                te.recorded_at
            FROM time_entries te
            INNER JOIN users u ON te.user_id = u.id
            LEFT JOIN users m ON u.manager_id = m.id
        ";

        $params = [];

        if ($startDateTime && $endDateTime) {
            $query .= " WHERE date(te.recorded_at) BETWEEN date(?) AND date(?)";
            $params[] = $startDateTime->format('Y-m-d H:i:s');
            $params[] = $endDateTime->format('Y-m-d H:i:s');
        } elseif ($startDateTime instanceof Carbon) {
            $query .= " WHERE date(te.recorded_at) >= date(?)";
            $params[] = $startDateTime->format('Y-m-d H:i:s');
        } elseif ($endDateTime instanceof Carbon) {
            $query .= " WHERE date(te.recorded_at) <= date(?)";
            $params[] = $endDateTime->format('Y-m-d H:i:s');
        }

        $query .= " ORDER BY te.recorded_at DESC";

        return DB::select($query, $params);
    }

    /**
     * @inheritdoc
     */
    public function findByUserAndDateRange(
        int $userId,
        Carbon $startDate,
        Carbon $endDate,
        array $requestData = []
    ): LengthAwarePaginator {
        return TimeEntry::query()
            ->with('user')
            ->inDateRange(
                $startDate->startOfDay(),
                $endDate->endOfDay(),
            )
            ->where('user_id', $userId)
            ->orderBy('recorded_at', 'desc')
            ->paginate(15)
            ->appends($requestData);
    }

    /**
     * @inheritdoc
     */
    public function hasUserRegisteredToday(int $userId): bool
    {
        return TimeEntry::todayForUser($userId)->exists();
    }

    /**
     * @inheritdoc
     */
    public function create(array $data): TimeEntry
    {
        return TimeEntry::query()->create($data);
    }

    /**
     * @inheritdoc
     */
    public function findLastByUser(int $userId): ?TimeEntry
    {
        return TimeEntry::query()
            ->where('user_id', $userId)
            ->latest('recorded_at')
            ->first();
    }

    /**
     * @inheritdoc
     */
    public function hasUserRegisteredInTimeInterval(int $userId, int $seconds): bool
    {
        $startTime = now()->subSeconds($seconds);
        
        return TimeEntry::query()
            ->where('user_id', $userId)
            ->where('recorded_at', '>=', $startTime)
            ->exists();
    }

    /**
     * @inheritdoc
     */
    public function countTodayEntriesByUser(int $userId): int
    {
        return TimeEntry::query()
            ->where('user_id', $userId)
            ->whereDate('recorded_at', now()->toDateString())
            ->count();
    }
}
