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
        'price',
        'wholesale_price',
        'cost',
        'stock',
        'image',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
            'cost' => 'decimal:2',
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

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
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