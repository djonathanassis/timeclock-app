<?php

declare(strict_types = 1);

namespace App\Enums;

enum JobPosition: string
{
    case DEVELOPER = 'developer';
    case ANALYST   = 'analyst';
    case MANAGER   = 'manager';
    case OTHER     = 'other';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
