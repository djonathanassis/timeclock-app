<?php

declare(strict_types = 1);

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User;
    
    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User;
    
    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool;

    /**
     * @param int $managerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByManager(int $managerId, int $perPage = 10): LengthAwarePaginator;
} 
