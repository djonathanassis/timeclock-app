<?php

declare(strict_types = 1);

namespace App\Services\TimeEntry;

use App\Models\TimeEntry;
use App\Repositories\TimeEntry\TimeEntryRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\TimeEntry\Interfaces\TimeEntryServiceInterface;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class TimeEntryService implements TimeEntryServiceInterface
{
    private int $minInterval;

    private int $maxEntriesPerDay;
    
    public function __construct(
        private TimeEntryRepositoryInterface $repository,
        private UserRepositoryInterface $userRepository
    ) {
        $this->minInterval = config('time-entry.min_interval_between_entries', 120);
        $this->maxEntriesPerDay = config('time-entry.max_entries_per_day', 4);
    }

    /**
     * @inheritdoc
     */
    public function listTimeEntries(
        int $userId,
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        array $requestData = []
    ): LengthAwarePaginator {
        return $this->repository->findByUserAndDateRange($userId, $startDate, $endDate, $requestData);
    }

    /**
     * @inheritdoc
     */
    public function registerTimeEntry(int $userId): TimeEntry
    {
        $user = $this->userRepository->findById($userId);
        
        if ($user === null) {
            throw new \RuntimeException('Usuário não encontrado.');
        }

        if ($this->repository->hasUserRegisteredInTimeInterval($userId, $this->minInterval)) {
            $lastEntry = $this->getLastTimeEntry($userId);
            $remaining = $this->minInterval;
            
            if ($lastEntry) {
                $remaining = $this->minInterval - now()->diffInSeconds($lastEntry->recorded_at);
            }
            
            throw new \RuntimeException(
                "Aguarde mais {$remaining} segundos para registrar um novo ponto."
            );
        }

        $todayCount = $this->countTodayEntriesByUser($userId);
        if ($todayCount >= $this->maxEntriesPerDay) {
            throw new \RuntimeException(
                "Você já atingiu o limite de " . $this->maxEntriesPerDay . " registros para hoje."
            );
        }

        DB::beginTransaction();
        try {
            $timeEntry = $this->repository->create([
                'user_id'     => $userId,
                'recorded_at' => now(),
            ]);

            DB::commit();
            return $timeEntry;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Erro ao registrar ponto: ' . $e->getMessage(), [
                'user_id' => $userId,
                'exception' => $e,
            ]);
            
            throw new \RuntimeException(
                'Erro ao registrar ponto: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function canRegisterTimeEntry(int $userId): bool
    {
        if ($this->repository->hasUserRegisteredInTimeInterval($userId, $this->minInterval)) {
            return false;
        }

        $todayCount = $this->countTodayEntriesByUser($userId);
        return $todayCount < $this->maxEntriesPerDay;
    }

    /**
     * @inheritdoc
     */
    public function getLastTimeEntry(int $userId): ?TimeEntry
    {
        return $this->repository->findLastByUser($userId);
    }
    
    /**
     * @inheritdoc
     */
    public function countTodayEntriesByUser(int $userId): int
    {
        return $this->repository->countTodayEntriesByUser($userId);
    }
}
