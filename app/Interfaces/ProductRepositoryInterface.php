<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

interface ProductRepositoryInterface
{
    public function getAll(): Collection;

    /**
     * Get paginated products with optional search
     *
     * @param int $perPage
     * @param string|null $search
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator;

    public function getById($id);

    public function create(array $data);

    public function update($id, array $data): bool;

    public function delete($id): bool;

    public function findByCode(string $code);

    public function updateStock($id, $quantity, ?string $role = null): bool;

    public function getActiveForPos(?string $role = null): Collection;

    public function getLowStock(int $threshold): Collection;

    public function getCategories(): SupportCollection;

    /**
     * Search products by name, code, or category
     *
     * @param string $searchTerm
     * @return Collection
     */
    public function search(string $searchTerm): Collection;
}