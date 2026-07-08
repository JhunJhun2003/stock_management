<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const LOW_STOCK_THRESHOLD = 5;

    protected $fillable = [
        'product_code',
        'product_name',
        'category',
        'home_cost',
        'shop_cost',
        'home_price',
        'shop_price',
        'home_stock',
        'shop_stock',
        'image',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'home_cost' => 'decimal:2',
            'shop_cost' => 'decimal:2',
            'home_price' => 'decimal:2',
            'shop_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    // Helper methods
    public function getStockForRole(?string $role): int
    {
        return match ($role) {
            User::ROLE_SHOP => (int) ($this->shop_stock ?? 0),
            default => (int) ($this->home_stock ?? 0),
        };
    }

    protected function stockColumnForRole(?string $role): string
    {
        return $role === User::ROLE_SHOP ? 'shop_stock' : 'home_stock';
    }

    public function hasStock($quantity, ?string $role = null): bool
    {
        return $this->getStockForRole($role) >= $quantity;
    }

    public function decreaseStock($quantity, ?string $role = null)
    {
        $column = $this->stockColumnForRole($role);
        $this->{$column} -= $quantity;
        $this->save();
        return $this;
    }

    public function increaseStock($quantity, ?string $role = null)
    {
        $column = $this->stockColumnForRole($role);
        $this->{$column} += $quantity;
        $this->save();
        return $this;
    }

    public function getCostForRole(?string $role): float
    {
        return match ($role) {
            User::ROLE_SHOP => (float) ($this->shop_cost ?? 0),
            default => (float) ($this->home_cost ?? 0),
        };
    }

    public function getPriceForRole(?string $role): float
    {
        return match ($role) {
            User::ROLE_SHOP => (float) ($this->shop_price ?? 0),
            default => (float) ($this->home_price ?? 0),
        };
    }

    public function getPriceAttribute($value): float
    {
        return $this->getPriceForRole(User::ROLE_HOME);
    }

    public function getWholesalePriceAttribute($value): float
    {
        return $this->getPriceForRole(User::ROLE_SHOP);
    }

    public function getCostAttribute($value): float
    {
        return $this->getCostForRole(User::ROLE_HOME);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->getPriceForRole(User::ROLE_HOME), 2);
    }

    public function isLowStock(?string $role = null): bool
    {
        if ($role === null) {
            $homeLow = $this->home_stock > 0 && $this->home_stock < self::LOW_STOCK_THRESHOLD;
            $shopLow = $this->shop_stock > 0 && $this->shop_stock < self::LOW_STOCK_THRESHOLD;

            return $homeLow || $shopLow;
        }

        $stock = $this->getStockForRole($role);

        return $stock > 0 && $stock < self::LOW_STOCK_THRESHOLD;
    }

    public function isOutOfStock(?string $role = null): bool
    {
        if ($role === null) {
            return $this->home_stock <= 0 && $this->shop_stock <= 0;
        }

        return $this->getStockForRole($role) <= 0;
    }

    public function getStockStatus(): string
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        }

        if ($this->isLowStock()) {
            return 'low_stock';
        }

        return 'in_stock';
    }
}