<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\User\DTOs\UserDTO;
use App\Services\User\Interfaces\UserServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class UserService implements UserServiceInterface
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * @param UserDTO $userDTO
     * @return User
     */
    public function createUser(UserDTO $userDTO): User
    {
       return $this->userRepository->create($userDTO->toArray());
    }

    /**
     * @param User $user
     * @param UserDTO $userDTO
     * @return User
     */
    public function updateUser(User $user, UserDTO $userDTO): User
    {
        return $this->userRepository->update($user, $userDTO->toArray());
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user): bool
    {
        if (!$user->exists) {
            return false;
        }

       return $this->userRepository->delete($user);
    }

    /**
     * @param int $managerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUsersByManager(int $managerId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->getByManager($managerId, $perPage);
    }

    /**
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAll();
    }

    /**
     * @param int $userId
     * @return User|null
     */
    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }
}
