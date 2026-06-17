<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'product_name',
        'category',
        'price',
        'stock',
        'image',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
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
}