<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\SaleRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\SaleRepository;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            SaleRepositoryInterface::class,
            SaleRepository::class
        );

        $this->app->bind(
            \App\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
