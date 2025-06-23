<?php

declare(strict_types = 1);

namespace App\Services\User\Interfaces;

use App\Exceptions\CannotDeleteUserException;
use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Services\User\DTOs\UserDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    /**
     * @param UserDTO $userDTO
     * @throws UserAlreadyExistsException
     * @return User
     */
    public function createUser(UserDTO $userDTO): User;
    
    /**
     * @param User $user
     * @param UserDTO $userDTO
     * @throws UserNotFoundException
     * @throws UserAlreadyExistsException
     * @return User
     */
    public function updateUser(User $user, UserDTO $userDTO): User;
    
    /**
     * @param User $user
     * @throws CannotDeleteUserException
     * @return bool
     */
    public function deleteUser(User $user): bool;

    /**
     * @param int $managerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUsersByManager(int $managerId, int $perPage = 10): LengthAwarePaginator;
}
