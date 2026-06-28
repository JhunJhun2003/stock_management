<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function getAll(): Collection
    {
        return User::all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return User::orderBy('id', 'asc')->paginate($perPage);
    }

    public function getById(int $id): ?User
    {
        return User::where('id', '=', $id, 'and')->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = User::where('id', '=', $id, 'and')->first();
        if (!$user) {
            return false;
        }

        // Use query update to avoid instance-method analyzer issues
        $updated = User::where('id', '=', $id, 'and')->update($data);
        return (bool) $updated;
    }

    public function delete(int $id): bool
    {
        $deleted = User::where('id', '=', $id, 'and')->delete();
        return (bool) $deleted;
    }
}
