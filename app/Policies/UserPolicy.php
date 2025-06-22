<?php

declare(strict_types = 1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    /**
     * @param User $user
     * @param User $employee
     * @return bool
     */
    public function view(User $user, User $employee): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        return $user->id === $employee->id;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN->value;
    }

    /**
     * @param User $user
     * @param User $employee
     * @return bool
     */
    public function update(User $user, User $employee): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($employee->manager_id === $user->id) {
            return true;
        }

        return $user->id === $employee->id;
    }

    /**
     * @param User $user
     * @param User $employee
     * @return bool
     */
    public function delete(User $user, User $employee): bool
    {
        if ($user->role !== UserRole::ADMIN) {
            return false;
        }

        return $employee->role === UserRole::EMPLOYEE;
    }

    /**
     * @param User $user
     * @param User $employee
     * @return bool
     */
    public function viewTimeEntries(User $user, User $employee): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        return $user->id === $employee->id;
    }
} 
