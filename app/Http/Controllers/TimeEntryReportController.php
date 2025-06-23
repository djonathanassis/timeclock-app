<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\TimeEntryReportRequest;
use App\Models\TimeEntry;
use App\Reports\GetTimeRecordReport;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class TimeEntryReportController extends Controller
{
    /**
     * @throws Exception
     * @throws AuthorizationException
     */
    public function __invoke(
        TimeEntryReportRequest $request,
        GetTimeRecordReport $report
    ): View {
        $this->authorize('report', TimeEntry::class);
        
        $startDate = $request->getStartDateTime();
        $endDate   = $request->getEndDateTime();

        $entries = $report->execute($startDate, $endDate);

        return view('time-entries.report', [
            'entries'   => $entries,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }
}
