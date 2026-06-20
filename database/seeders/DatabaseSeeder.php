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
            ['product_code' => 'P001', 'product_name' => 'Laptop', 'category' => 'Electronics', 'price' => ၅၀၀၀၀၀, 'stock' => ၁၀],
            ['product_code' => 'P002', 'product_name' => 'Smartphone', 'category' => 'Electronics', 'price' => ၃၀၀၀၀၀, 'stock' => ၁၅],
            ['product_code' => 'P003', 'product_name' => 'Headphones', 'category' => 'Accessories', 'price' => ၅၀၀၀၀, 'stock' => ၃၀],
            ['product_code' => 'P004', 'product_name' => 'Keyboard', 'category' => 'Accessories', 'price' => ၁၀၀၀၀, 'stock' => ၃၀],
            ['product_code' => 'P005', 'product_name' => 'Monitor', 'category' => 'Electronics', 'price' => ၂၀၀၀၀, 'stock' => ၈],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}