<?php

declare(strict_types = 1);

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class DateHelper
{
    /**
     * @param string|null $date
     * @param CarbonInterface $default
     * @return CarbonInterface
     */
    public static function parseDate(?string $date, CarbonInterface $default): CarbonInterface
    {
        return $date !== null && $date !== '' && $date !== '0'
            ? Carbon::parse($date)
            : $default;
    }
}
