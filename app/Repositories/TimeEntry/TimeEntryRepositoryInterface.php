<?php

declare(strict_types = 1);

namespace App\Repositories\TimeEntry;

use App\Models\TimeEntry;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TimeEntryRepositoryInterface
{
    /**
     * @param Carbon|null $startDateTime
     * @param Carbon|null $endDateTime
     * @return array
     */
    public function getTimeRecordReport(
        ?Carbon $startDateTime = null,
        ?Carbon $endDateTime = null
    ): array;

    /**
     * @param int $userId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param array $requestData
     */
    public function findByUserAndDateRange(
        int $userId,
        Carbon $startDate,
        Carbon $endDate,
        array $requestData = []
    ): LengthAwarePaginator;

    /**
     * @param int $userId
     */
    public function hasUserRegisteredToday(int $userId): bool;

    /**
     * @param array $data
     */
    public function create(array $data): TimeEntry;

    /**
     * @param int $userId
     */
    public function findLastByUser(int $userId): ?TimeEntry;

    /**
     * @param int $userId 
     * @param int $seconds
     * @return bool
     */
    public function hasUserRegisteredInTimeInterval(int $userId, int $seconds): bool;
    
    /**
     * @param int $userId
     * @return int
     */
    public function countTodayEntriesByUser(int $userId): int;
}
