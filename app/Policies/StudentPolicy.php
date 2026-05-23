<?php

namespace App\Policies;

use App\Models\Student\Student;
use App\Models\User;

class StudentPolicy
{
    private const FULL_ACCESS_ROLES = ['Admin', 'Super Admin','Manager','Counselor'];

    public function manage(User $user, Student $student): bool
    {
        $roleType = optional($user->role)->user_type;

        if (in_array($roleType, self::FULL_ACCESS_ROLES, true)) {
            return true;
        }

        return (int) $user->branch === (int) $student->branch_id;
    }

    public function updateOfficeStatus(User $user, Student $student): bool
    {
        return $this->manage($user, $student);
    }

    public function updateApplicationStatus(User $user, Student $student): bool
    {
        return $this->manage($user, $student);
    }

    public function view(User $user, Student $student): bool
    {
        return $this->manage($user, $student);
    }

    public function viewPortalCredentials(User $user, Student $student): bool
    {
        $roleIds = config('student.portal_credential_role_ids', [1, 2, 3]);

        return in_array((int) $user->role_id, $roleIds, true);
    }
}
