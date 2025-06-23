<?php

declare(strict_types = 1);

namespace App\Helpers;

use Illuminate\Support\Carbon;

class DateHelper
{
    /**
     * @param string|null $date
     * @param Carbon $default
     * @return Carbon
     */
    public static function parseDate(?string $date, Carbon $default): Carbon
    {
        return $date !== null && $date !== '' && $date !== '0'
            ? Carbon::parse($date)
            : $default;
    }
}
