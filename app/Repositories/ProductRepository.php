<?php

namespace App\Repositories;

use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(): Collection
    {
        return Product::all();
    }

    public function getById($id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update($id, array $data): bool
    {
        $product = Product::find($id);
        if ($product) {
            return $product->update($data);
        }
        return false;
    }

    public function delete($id): bool
    {
        $product = Product::find($id);
        if ($product) {
            return $product->delete();
        }
        return false;
    }

    public function findByCode($code): ?Product
    {
        return Product::where('code', $code)->first();
    }

    public function updateStock($id, $quantity): bool
    {
        $product = Product::find($id);
        if ($product) {
            $product->stock += $quantity;
            return $product->save();
        }
        return false;
    }
}