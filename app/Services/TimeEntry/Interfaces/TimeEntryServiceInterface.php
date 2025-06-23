<?php

declare(strict_types = 1);

namespace App\Services\TimeEntry\Interfaces;

use App\Models\TimeEntry;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

interface TimeEntryServiceInterface
{
    /**
     * @param int $userId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param array $requestData
     * @return LengthAwarePaginator
     */
    public function listTimeEntries(
        int $userId,
        Carbon $startDate,
        Carbon $endDate,
        array $requestData = []
    ): LengthAwarePaginator;

    /**
     * @param int $userId
     * @return TimeEntry
     */
    public function registerTimeEntry(int $userId): TimeEntry;

    /**
     * @param int $userId
     * @return bool
     */
    public function canRegisterTimeEntry(int $userId): bool;

    /**
     * @param int $userId
     * @return TimeEntry|null
     */
    public function getLastTimeEntry(int $userId): ?TimeEntry;

    /**
     * @param int $userId
     * @return int
     */
    public function countTodayEntriesByUser(int $userId): int;
}
