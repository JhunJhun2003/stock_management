<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getAll(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function getById(int $id): ?User;

    public function create(array $data): User;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
