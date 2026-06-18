<?php

namespace App\Repositories;

use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(): Collection
    {
        return Product::all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Product::latest()->paginate($perPage);
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

        if (!$product) {
            return false;
        }

        return $product->update($data);
    }

    public function delete($id): bool
    {
        $product = Product::find($id);

        if (!$product) {
            return false;
        }

        return (bool) $product->delete();
    }

    public function findByCode(string $code): ?Product
    {
        return Product::where('product_code', $code)->first();
    }

    public function updateStock($id, $quantity): bool
    {
        $product = Product::find($id);

        if (!$product) {
            return false;
        }

        $product->stock += $quantity;

        return $product->save();
    }

    public function getActiveForPos(): Collection
    {
        return Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('product_name')
            ->get();
    }

    public function getLowStock(int $threshold): Collection
    {
        return Product::where('stock', '>', 0)
            ->where('stock', '<', $threshold)
            ->orderBy('stock')
            ->get();
    }

    public function getCategories(): SupportCollection
    {
        return Product::where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }
}
