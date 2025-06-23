<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Models\TimeEntry;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

/**
 * @property string $start_date
 * @property string $end_date
 */
class TimeEntryReportRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date', 'before_or_equal:today'],
            'end_date'   => ['nullable', 'date', 'before_or_equal:today', 'after_or_equal:start_date'],
        ];
    }

    /**
     * @throws Exception
     */
    public function getStartDateTime(): ?Carbon
    {
        if ($this->filled('start_date')) {
            return Carbon::parse($this->start_date)->startOfDay();
        }

        return null;
    }

    /**
     * @return Carbon|null
     * @throws Exception
     */
    public function getEndDateTime(): ?Carbon
    {
        if ($this->filled('end_date')) {
            return Carbon::parse($this->end_date)->endOfDay();
        }

        return null;
    }
}
