<?php

namespace App\Support;

use App\Models\User;

class FileStudentIdAccess
{
    /** @var list<string> */
    public const EDITOR_ROLES = [
        'Super Admin',
        'Admin',
        'Manager',
        'IT Officer',
    ];

    public static function canEdit(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return in_array(optional($user->role)->user_type, self::EDITOR_ROLES, true);
    }
}
