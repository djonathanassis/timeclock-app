<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\TimeEntryReportRequest;
use App\Reports\GetTimeRecordReport;
use Exception;
use Illuminate\View\View;

class TimeEntryReportController extends Controller
{
    /**
     * @param TimeEntryReportRequest $request
     * @param GetTimeRecordReport $report
     * @return View
     * @throws Exception
     */
    public function __invoke(
        TimeEntryReportRequest $request,
        GetTimeRecordReport $report
    ): View {
        $startDate = $request->getStartDateTime();
        $endDate = $request->getEndDateTime();

        $entries = $report->execute($startDate, $endDate);

        return view('time-entries.report', [
            'entries'   => $entries,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }
}
