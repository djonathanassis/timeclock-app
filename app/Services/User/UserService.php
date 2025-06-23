<?php

declare(strict_types = 1);

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\User\DTOs\UserDTO;
use App\Services\User\Interfaces\UserServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @inheritdoc
     */
    public function createUser(UserDTO $userDTO): User
    {
        return $this->userRepository->create($userDTO->toArray());
    }

    /**
     * @inheritdoc
     */
    public function updateUser(User $user, UserDTO $userDTO): User
    {
        return $this->userRepository->update($user, $userDTO->toArray());
    }

    /**
     * @inheritdoc
     */
    public function deleteUser(User $user): bool
    {
        if (! $user->exists) {
            return false;
        }

        return $this->userRepository->delete($user);
    }

    /**
     * @inheritdoc
     */
    public function getUsersByManager(int $managerId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->getByManager($managerId, $perPage);
    }

    /**
     * @inheritdoc
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAll();
    }

    /**
     * @inheritdoc
     */
    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }
}
