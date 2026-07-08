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
        return Product::where('is_active', true)
            ->orderBy('product_name')
            ->get();
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
}
