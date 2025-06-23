<?php

declare(strict_types = 1);

namespace App\Repositories\TimeEntry;

use App\Models\TimeEntry;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TimeEntryRepositoryInterface
{
    /**
     * @param CarbonInterface|null $startDateTime
     * @param CarbonInterface|null $endDateTime
     * @return array
     */
    public function getTimeRecordReport(
        ?CarbonInterface $startDateTime = null,
        ?CarbonInterface $endDateTime = null
    ): array;

    /**
     * @param int $userId
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @param array $requestData
     */
    public function findByUserAndDateRange(
        int $userId,
        CarbonInterface $startDate,
        CarbonInterface $endDate,
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
