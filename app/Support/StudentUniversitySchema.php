<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;

class StudentUniversitySchema
{
    /** @var array<string, bool>|null */
    private static $portalColumns;

    public static function hasPortalType(): bool
    {
        return self::portalColumns()['portal_type'];
    }

    public static function hasLoginId(): bool
    {
        return self::portalColumns()['login_id'];
    }

    public static function hasLoginPassword(): bool
    {
        return self::portalColumns()['login_password'];
    }

    /**
     * @return array<string, bool>
     */
    private static function portalColumns(): array
    {
        if (self::$portalColumns !== null) {
            return self::$portalColumns;
        }

        self::$portalColumns = [
            'portal_type' => Schema::hasColumn('student_university', 'portal_type'),
            'login_id' => Schema::hasColumn('student_university', 'login_id'),
            'login_password' => Schema::hasColumn('student_university', 'login_password'),
        ];

        return self::$portalColumns;
    }
}
