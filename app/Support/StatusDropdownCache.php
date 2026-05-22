<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

final class StatusDropdownCache
{
    public const STUDENT_STATUSES = 'dropdown.student_statuses';

    public const UNIVERSITY_STATUSES = 'dropdown.university_statuses';

    public static function forgetStudentStatuses(): void
    {
        Cache::forget(self::STUDENT_STATUSES);
    }

    public static function forgetUniversityStatuses(): void
    {
        Cache::forget(self::UNIVERSITY_STATUSES);
    }

    public static function forgetAll(): void
    {
        self::forgetStudentStatuses();
        self::forgetUniversityStatuses();
    }
}
