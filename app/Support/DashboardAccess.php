<?php

namespace App\Support;

use App\Models\User;

/**
 * Role-based dashboard visibility (maps to roles.user_type / role_id).
 */
final class DashboardAccess
{
    /**
     * @return array{
     *     role_id: int,
     *     role_label: string,
     *     scope: string,
     *     counselor_only: bool,
     *     all_branches: bool,
     *     sections: array<string, bool>
     * }
     */
    public static function for(User $user): array
    {
        $roleId = (int) $user->role_id;
        $sections = config('dashboard.permissions', []);

        $resolved = [];
        foreach ($sections as $section => $allowedRoleIds) {
            $resolved[$section] = in_array($roleId, (array) $allowedRoleIds, true);
        }

        $counselorOnly = in_array($roleId, config('dashboard.counselor_scope_role_ids', [8]), true);
        $allBranches = in_array($roleId, config('dashboard.all_branches_role_ids', [1, 2]), true);

        return [
            'role_id' => $roleId,
            'role_label' => optional($user->role)->user_type ?? 'User',
            'scope' => self::resolveScopeLabel($roleId, $counselorOnly, $allBranches),
            'counselor_only' => $counselorOnly,
            'all_branches' => $allBranches,
            'sections' => $resolved,
        ];
    }

    public static function can(User $user, string $section): bool
    {
        $access = self::for($user);

        return (bool) ($access['sections'][$section] ?? false);
    }

    public static function seesBranchOverview(User $user): bool
    {
        return in_array(
            (int) $user->role_id,
            config('dashboard.branch_overview_role_ids', [1, 2, 3, 4]),
            true
        );
    }

    private static function resolveScopeLabel(int $roleId, bool $counselorOnly, bool $allBranches): string
    {
        if ($allBranches) {
            return 'All branches';
        }

        if ($counselorOnly) {
            return 'My assigned students';
        }

        if (in_array($roleId, config('dashboard.branch_overview_role_ids', []), true)) {
            return 'Branch overview';
        }

        if (in_array($roleId, config('dashboard.permissions.kpi_revenue', []), true)) {
            return 'Finance overview';
        }

        return 'Limited overview';
    }
}
