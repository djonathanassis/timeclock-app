<?php

declare(strict_types = 1);

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $data): User
    {
        return User::query()->create($data);
    }
    
    /**
     * @inheritDoc
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
    
    /**
     * @inheritDoc
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * @inheritDoc
     */
    public function getByManager(int $managerId, int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->where('manager_id', $managerId)
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): Collection
    {
        return User::query()->orderBy('name')->get();
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id): ?User
    {
        return User::query()->find($id);
    }
}
