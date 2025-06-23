<?php

declare(strict_types = 1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\TimeEntry;
use App\Models\User;

class TimeEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TimeEntry $timeEntry): bool
    {
        if ($user->role->value === UserRole::ADMIN->value) {
            return true;
        }

        return $user->id === (int) $timeEntry->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TimeEntry $timeEntry): bool
    {
        return $user->role->value === UserRole::ADMIN->value;
    }

    public function delete(User $user, TimeEntry $timeEntry): bool
    {
        return $user->role->value === UserRole::ADMIN->value;
    }

    public function report(User $user): bool
    {
        return $user->role->value === UserRole::ADMIN->value;
    }

    public function registerTimeEntry(User $user): bool
    {
        return true;
    }

    public function restore(User $user, TimeEntry $timeEntry): bool
    {
        return $user->role->value === UserRole::ADMIN->value;
    }

    public function forceDelete(User $user, TimeEntry $timeEntry): bool
    {
        return $user->role->value === UserRole::ADMIN->value;
    }

    public function viewOtherUserEntries(User $user): bool
    {
        return $user->role->value === UserRole::ADMIN->value;
    }
}
