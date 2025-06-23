<?php

declare(strict_types = 1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role->value === UserRole::ADMIN->value;
    }

    public function view(User $user, User $employee): bool
    {
        if ($user->role->value === UserRole::ADMIN->value) {
            return true;
        }

        return $user->id === $employee->id;
    }

    public function create(User $user): bool
    {
        return $user->role->value === UserRole::ADMIN->value;
    }

    public function update(User $user, User $employee): bool
    {
        if ($user->role->value === UserRole::ADMIN->value) {
            return true;
        }

        if ($employee->manager_id === $user->id) {
            return true;
        }

        return $user->id === $employee->id;
    }

    public function delete(User $user, User $employee): bool
    {
        if ($user->role->value !== UserRole::ADMIN->value) {
            return false;
        }

        return $employee->role->value === UserRole::EMPLOYEE->value;
    }

    public function viewTimeEntries(User $user, User $employee): bool
    {
        if ($user->role->value === UserRole::ADMIN->value) {
            return true;
        }

        return $user->id === $employee->id;
    }

    public function updateProfile(User $user, User $profile): bool
    {
        return $user->id === $profile->id;
    }
}
