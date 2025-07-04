<?php

declare(strict_types = 1);

namespace App\Enums;

enum UserRole: string
{
    case ADMIN    = 'admin';
    case EMPLOYEE = 'employee';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
