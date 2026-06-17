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

    /**
     * Get settings (singleton pattern).
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'shop_name' => 'My POS Shop',
                'currency' => 'Ks.',
                'currency_code' => 'MMK',
                'address' => 'Yangon, Myanmar',
                'phone' => '+95 9 123456789',
                'email' => 'shop@example.com',
                'tax_rate' => 0,
            ]);
        }
        
        return $settings;
    }
}