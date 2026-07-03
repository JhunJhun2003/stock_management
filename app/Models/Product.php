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
        'stock',
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
    public function hasStock($quantity)
    {
        return $this->stock >= $quantity;
    }

    public function decreaseStock($quantity)
    {
        $this->stock -= $quantity;
        $this->save();
        return $this;
    }

    public function increaseStock($quantity)
    {
        $this->stock += $quantity;
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

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock < self::LOW_STOCK_THRESHOLD;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
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