<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAll();
    }

    public function getProductById($id): ?object
    {
        return $this->productRepository->getById($id);
    }

    public function createProduct(array $data): object
    {
        // Validate unique code before creating
        if ($this->productRepository->findByCode($data['code'])) {
            throw new \Exception('Product code already exists');
        }
        
        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data): bool
    {
        // If code is being updated, check uniqueness
        if (isset($data['code'])) {
            $existing = $this->productRepository->findByCode($data['code']);
            if ($existing && $existing->id != $id) {
                throw new \Exception('Product code already exists');
            }
        }
        
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct($id): bool
    {
        return $this->productRepository->delete($id);
    }

    public function getProductByCode($code): ?object
    {
        return $this->productRepository->findByCode($code);
    }

    public function updateStock($id, $quantity): bool
    {
        if ($quantity == 0) {
            throw new \Exception('Quantity must be non-zero');
        }
        return $this->productRepository->updateStock($id, $quantity);
    }
}