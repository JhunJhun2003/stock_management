<?php

use App\Models\Product;
use App\Models\User;

it('maps user roles correctly', function () {
    $admin = new User(['role' => User::ROLE_ADMIN]);
    $home = new User(['role' => User::ROLE_HOME]);
    $shop = new User(['role' => User::ROLE_SHOP]);

    expect($admin->isAdmin())->toBeTrue()
        ->and($admin->isHome())->toBeFalse()
        ->and($admin->isShop())->toBeFalse()
        ->and($home->isHome())->toBeTrue()
        ->and($shop->isShop())->toBeTrue();
});

it('returns role-based product pricing and costs', function () {
    $product = new Product([
        'home_cost' => 100,
        'shop_cost' => 120,
        'home_price' => 150,
        'shop_price' => 180,
        'home_stock' => 10,
        'shop_stock' => 5,
    ]);

    expect($product->getCostForRole(User::ROLE_HOME))->toEqual(100.0)
        ->and($product->getCostForRole(User::ROLE_SHOP))->toEqual(120.0)
        ->and($product->getPriceForRole(User::ROLE_HOME))->toEqual(150.0)
        ->and($product->getPriceForRole(User::ROLE_SHOP))->toEqual(180.0)
        ->and($product->getStockForRole(User::ROLE_HOME))->toBe(10)
        ->and($product->getStockForRole(User::ROLE_SHOP))->toBe(5);
});
