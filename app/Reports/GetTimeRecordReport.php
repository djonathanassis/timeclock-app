<?php

declare(strict_types = 1);

namespace App\Reports;

use App\Repositories\TimeEntry\TimeEntryRepositoryInterface;
use Illuminate\Support\Carbon;

readonly class GetTimeRecordReport
{
    public function __construct(
        private TimeEntryRepositoryInterface $repository
    ) {
    }

    public function execute(
        ?Carbon $startDateTime = null,
        ?Carbon $endDateTime = null
    ): array {
        return $this->repository->getTimeRecordReport($startDateTime, $endDateTime);
    }
}
