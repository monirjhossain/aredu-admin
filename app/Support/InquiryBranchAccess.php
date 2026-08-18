<?php

namespace App\Support;

use App\Models\Inquiry\Inquiry;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class InquiryBranchAccess
{
    private const FULL_ACCESS_ROLES = ['Admin', 'Super Admin', 'Manager'];

    public static function canManageAnyBranch(User $user): bool
    {
        $roleType = optional($user->role)->user_type;

        return in_array($roleType, self::FULL_ACCESS_ROLES, true);
    }

    public static function canAccessInquiry(User $user, Inquiry $inquiry): bool
    {
        if (self::canManageAnyBranch($user)) {
            return true;
        }

        return (int) $user->branch === (int) $inquiry->branch_id;
    }

    public static function resolveBranchFilter(User $user, $requestedBranchId = null): ?int
    {
        if ($requestedBranchId !== null && $requestedBranchId !== '') {
            $branchId = (int) $requestedBranchId;

            if (self::canManageAnyBranch($user)) {
                return $branchId;
            }

            return (int) $user->branch === $branchId ? $branchId : (int) $user->branch;
        }

        if (self::canManageAnyBranch($user)) {
            return null;
        }

        return $user->branch ? (int) $user->branch : null;
    }

    public static function applyListScope(Builder $query, User $user, ?int $branchId): Builder
    {
        if ($branchId) {
            return $query->where('branch_id', $branchId);
        }

        if (self::canManageAnyBranch($user)) {
            return $query;
        }

        if ($user->branch) {
            return $query->where('branch_id', $user->branch);
        }

        return $query->whereRaw('1 = 0');
    }
}
