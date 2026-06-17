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
            'name' => 'Seller User',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Create settings
        Setting::create([
            'shop_name' => 'My POS Shop',
            'currency' => 'Ks.',
            'currency_code' => 'MMK',
            'address' => 'Yangon, Myanmar',
            'phone' => '+95 9 123456789',
            'email' => 'shop@example.com',
            'tax_rate' => 0,
        ]);

        // Create sample products
        $products = [
            ['product_code' => 'P001', 'product_name' => 'Laptop', 'category' => 'Electronics', 'price' => 899.99, 'stock' => 10],
            ['product_code' => 'P002', 'product_name' => 'Smartphone', 'category' => 'Electronics', 'price' => 599.99, 'stock' => 15],
            ['product_code' => 'P003', 'product_name' => 'Headphones', 'category' => 'Accessories', 'price' => 49.99, 'stock' => 30],
            ['product_code' => 'P004', 'product_name' => 'Keyboard', 'category' => 'Accessories', 'price' => 29.99, 'stock' => 20],
            ['product_code' => 'P005', 'product_name' => 'Monitor', 'category' => 'Electronics', 'price' => 299.99, 'stock' => 8],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}