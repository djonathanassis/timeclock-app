<?php

declare(strict_types = 1);

namespace App\Reports;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class GetTimeRecordReport
{
    /**
     * @param CarbonInterface|null $startDateTime
     * @param CarbonInterface|null $endDateTime
     * @return array
     */
    public function execute(
        ?CarbonInterface $startDateTime = null,
        ?CarbonInterface $endDateTime = null
    ): array {
        $query = "
            SELECT 
                te.id,
                u.name AS employee_name,
                u.job_position,
                (strftime('%Y', 'now') - strftime('%Y', u.birth_date)) - 
                (strftime('%m-%d', 'now') < strftime('%m-%d', u.birth_date)) AS age,
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
        } elseif ($startDateTime instanceof CarbonInterface) {
            $query .= " WHERE date(te.recorded_at) >= date(?)";
            $params[] = $startDateTime->format('Y-m-d H:i:s');
        } elseif ($endDateTime instanceof CarbonInterface) {
            $query .= " WHERE date(te.recorded_at) <= date(?)";
            $params[] = $endDateTime->format('Y-m-d H:i:s');
        }

        $query .= " ORDER BY te.recorded_at DESC";

        return DB::select($query, $params);
    }
}
