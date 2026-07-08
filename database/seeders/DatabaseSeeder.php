<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Create seller user
        User::create([
            'name' => 'အရောင်းဝန်ထမ်း',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Create settings
        Setting::create([
            'shop_name' => 'ဆိုင်အမည်',
            'currency' => 'ကျပ်',
            'currency_code' => 'MMK',
            'address' => 'ရန်ကုန်၊ မြန်မာ',
            'phone' => '+၉၅ ၉၁၂၃၄၅၆၇၈၉',
            'email' => 'shop@example.com',
            'tax_rate' => 0,
        ]);

        // Create sample products
        $products = [
            ['product_code' => 'P001', 'product_name' => 'Laptop', 'category' => 'Electronics', 'home_cost' => 450000, 'shop_cost' => 460000, 'home_price' => 500000, 'shop_price' => 510000, 'home_stock' => 10, 'shop_stock' => 8],
            ['product_code' => 'P002', 'product_name' => 'Smartphone', 'category' => 'Electronics', 'home_cost' => 270000, 'shop_cost' => 280000, 'home_price' => 300000, 'shop_price' => 310000, 'home_stock' => 15, 'shop_stock' => 12],
            ['product_code' => 'P003', 'product_name' => 'Headphones', 'category' => 'Accessories', 'home_cost' => 45000, 'shop_cost' => 48000, 'home_price' => 50000, 'shop_price' => 52000, 'home_stock' => 30, 'shop_stock' => 25],
            ['product_code' => 'P004', 'product_name' => 'Keyboard', 'category' => 'Accessories', 'home_cost' => 9000, 'shop_cost' => 9500, 'home_price' => 10000, 'shop_price' => 10500, 'home_stock' => 30, 'shop_stock' => 20],
            ['product_code' => 'P005', 'product_name' => 'Monitor', 'category' => 'Electronics', 'home_cost' => 18000, 'shop_cost' => 19000, 'home_price' => 20000, 'shop_price' => 21000, 'home_stock' => 8, 'shop_stock' => 6],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}