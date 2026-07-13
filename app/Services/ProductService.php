<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use InvalidArgumentException;

class ProductService
{
    public const LOW_STOCK_THRESHOLD = 5;

    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function getPaginatedProducts(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->productRepository->paginate($perPage, $search);
    }

    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAll();
    }

    public function getProductById($id): ?object
    {
        return $this->productRepository->getById($id);
    }

    public function getActiveProductsForPos(?string $role = null): Collection
    {
        return $this->productRepository->getActiveForPos($role);
    }

    public function getLowStockProducts(): Collection
    {
        return $this->productRepository->getLowStock(self::LOW_STOCK_THRESHOLD);
    }

    public function getCategories(): SupportCollection
    {
        return $this->productRepository->getCategories();
    }

    public function createProduct(array $data): object
    {
        if ($this->productRepository->findByCode($data['product_code'])) {
            throw new InvalidArgumentException('Product code already exists.');
        }

        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data): bool
    {
        if (isset($data['product_code'])) {
            $existing = $this->productRepository->findByCode($data['product_code']);

            if ($existing && $existing->id != $id) {
                throw new InvalidArgumentException('Product code already exists.');
            }
        }

        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct($id): bool
    {
        return $this->productRepository->delete($id);
    }

    public function getProductByCode(string $code): ?object
    {
        return $this->productRepository->findByCode($code);
    }

    public function searchProducts(string $searchTerm): Collection
    {
        return $this->productRepository->search($searchTerm);
    }
}