<?php

declare(strict_types = 1);

namespace App\Reports;

use App\Repositories\TimeEntry\TimeEntryRepositoryInterface;
use Carbon\CarbonInterface;

readonly class GetTimeRecordReport
{
    public function __construct(
        private TimeEntryRepositoryInterface $repository
    ) {
    }

    public function execute(
        ?CarbonInterface $startDateTime = null,
        ?CarbonInterface $endDateTime = null
    ): array {
        return $this->repository->getTimeRecordReport($startDateTime, $endDateTime);
    }
}
