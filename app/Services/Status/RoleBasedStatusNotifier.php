<?php

namespace App\Services\Status;

use App\Models\Role;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class RoleBasedStatusNotifier
{
    private const NOTIFY_ROLE_TYPES = [
        'Admin',
        'Super Admin',
        'Assign Employee',
        'Senior Counselor',
        'Junior Counselor',
    ];

    public function broadcast(Notification $notification, ?User $studentUser = null): void
    {
        $actor = Auth::user();

        if ($actor && (!$studentUser || $actor->id !== $studentUser->id) && method_exists($actor, 'notify')) {
            $actor->notify($notification);
        }

        if ($studentUser) {
            $studentUser->notify($notification);
        }

        $roles = Role::whereIn('user_type', self::NOTIFY_ROLE_TYPES)->pluck('id');

        foreach (User::whereIn('role_id', $roles)->get() as $user) {
            if (($actor && $user->id === $actor->id) || ($studentUser && $user->id === $studentUser->id)) {
                continue;
            }

            if (method_exists($user, 'notify')) {
                $user->notify($notification);
            }
        }
    }
}
