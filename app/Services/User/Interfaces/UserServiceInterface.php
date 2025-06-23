<?php

declare(strict_types = 1);

namespace App\Services\User\Interfaces;

use App\Models\User;
use App\Services\User\DTOs\UserDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    /**
     * @param UserDTO $userDTO
     * @return User
     */
    public function createUser(UserDTO $userDTO): User;

    /**
     * @param User $user
     * @param UserDTO $userDTO
     * @return User
     */
    public function updateUser(User $user, UserDTO $userDTO): User;

    /**
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user): bool;

    /**
     * @return Collection
     */
    public function getAllUsers(): Collection;

    /**
     * @param int $userId
     * @return User|null
     */
    public function getUserById(int $userId): ?User;

    /**
     * @param int $managerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUsersByManager(int $managerId, int $perPage = 10): LengthAwarePaginator;
}
