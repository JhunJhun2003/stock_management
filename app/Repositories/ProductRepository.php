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

    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $query = Product::query();

        // Apply search filter if provided
        if ($search && !empty(trim($search))) {
            $searchTerm = trim($search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('product_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('product_code', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('category', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        return $query->latest()->paginate($perPage);
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

    public function updateStock($id, $quantity, ?string $role = null): bool
    {
        $product = Product::find($id);

        if (!$product) {
            return false;
        }

        $column = $role === \App\Models\User::ROLE_SHOP ? 'shop_stock' : 'home_stock';
        $product->{$column} += $quantity;

        return $product->save();
    }

    public function getActiveForPos(?string $role = null): Collection
    {
        $query = Product::where('is_active', true);

        // If role is not admin, only show products with stock
        if ($role !== 'admin') {
            $query->where(function ($q) {
                $q->where('home_stock', '>', 0)
                  ->orWhere('shop_stock', '>', 0);
            });
        }

        return $query->orderBy('product_name')->get();
    }

    public function getLowStock(int $threshold): Collection
    {
        return Product::where(function ($query) use ($threshold) {
            $query->where(function ($q) use ($threshold) {
                $q->where('home_stock', '>', 0)->where('home_stock', '<', $threshold);
            })->orWhere(function ($q) use ($threshold) {
                $q->where('shop_stock', '>', 0)->where('shop_stock', '<', $threshold);
            });
        })
            ->orderBy('home_stock')
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

    public function search(string $searchTerm): Collection
    {
        return Product::where(function ($q) use ($searchTerm) {
            $q->where('product_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('product_code', 'LIKE', "%{$searchTerm}%")
              ->orWhere('category', 'LIKE', "%{$searchTerm}%")
              ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        })->get();
    }
}