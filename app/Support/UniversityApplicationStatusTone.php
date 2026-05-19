<?php

namespace App\Support;

class UniversityApplicationStatusTone
{
  /** @var array<int, string> */
    private const MAP = [
        7 => 'success', 9 => 'success', 11 => 'success',
        4 => 'danger', 12 => 'danger', 2 => 'danger',
        6 => 'warning', 8 => 'warning', 13 => 'warning',
    ];

    public static function map(): array
    {
        return self::MAP;
    }

    public static function forStatusId(?int $statusId): string
    {
        return self::MAP[(int) $statusId] ?? 'primary';
    }
}
