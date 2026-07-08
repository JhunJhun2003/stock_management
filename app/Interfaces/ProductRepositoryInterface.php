<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

interface ProductRepositoryInterface
{
    public function getAll(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function getById($id);

    public function create(array $data);

    public function update($id, array $data): bool;

    public function delete($id): bool;

    public function findByCode(string $code);

    public function updateStock($id, $quantity, ?string $role = null): bool;

    public function getActiveForPos(?string $role = null): Collection;

    public function getLowStock(int $threshold): Collection;

    public function getCategories(): SupportCollection;
}
