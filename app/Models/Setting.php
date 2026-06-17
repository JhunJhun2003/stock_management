<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'currency',
        'currency_code',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'tax_rate',
        'system_config',
    ];

    protected function casts(): array
    {
        return [
            'system_config' => 'array',
            'tax_rate' => 'decimal:2',
        ];
    }

    // Helper method to get single setting
    public static function getSettings()
    {
        return self::first() ?? self::create([
            'shop_name' => 'My POS Shop',
            'currency' => '$',
            'currency_code' => 'MMK',
        ]);
    }
}