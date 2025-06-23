<?php

declare(strict_types = 1);

namespace App\Services\TimeEntry\Interfaces;

use App\Exceptions\MaxDailyEntriesException;
use App\Exceptions\TimeEntryIntervalException;
use App\Exceptions\TimeEntryRegistrationException;
use App\Models\TimeEntry;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TimeEntryServiceInterface
{
    /**
     * @param int $userId
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @param array $requestData
     * @return LengthAwarePaginator
     */
    public function listTimeEntries(
        int $userId,
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        array $requestData = []
    ): LengthAwarePaginator;

    /**
     * @param int $userId
     * @throws TimeEntryIntervalException
     * @throws MaxDailyEntriesException
     * @throws TimeEntryRegistrationException
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
