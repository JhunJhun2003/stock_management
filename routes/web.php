<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\SellerDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check() && Auth::user()->isSeller()) {
        return redirect()->route('seller.dashboard');
    }

    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    if (Auth::user()->isSeller()) {
        return redirect()->route('seller.dashboard');
    }

    return view('pos.dashboard', [
        'totalSales' => 100000,
        'totalOrders' => 100,
        'totalProducts' => \App\Models\Product::count(),
        'totalUsers' => \App\Models\User::count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/backup', [SettingsController::class, 'createBackup'])->name('settings.backup');
    Route::post('/settings/restore', [SettingsController::class, 'restoreBackup'])->name('settings.restore');
});

// Seller routes
Route::middleware(['auth', 'seller'])->group(function () {
    Route::get('/seller/dashboard', [SellerDashboardController::class, 'index'])
        ->name('seller.dashboard');

    Route::get('/pos', [PosController::class, 'index'])
        ->name('pos.index');

    Route::get('/sales-history', function () {
        return view('pos.sale_history');
    })->name('sales.history');
});

// Shared authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.password');
});

require __DIR__.'/auth.php';
