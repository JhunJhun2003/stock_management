<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    return view('pos.dashboard', [
        'totalSales' => 100000,
        'totalOrders' => 100,
        'totalProducts' => 200,
        'totalUsers' => 100,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Placeholder routes for menu items
Route::middleware(['auth'])->group(function () {
    Route::get('/users', function () { return view('pos.user_management'); })->name('users.index');
    Route::get('/products', function () { return view('pos.product_management'); })->name('products.index');
    Route::get('/reports', function () { return view('pos.report'); })->name('reports.index');
    Route::get('/settings', function () { return view('pos.setting'); })->name('settings.index');
    Route::get('/pos', function () { return view('pos.pos'); })->name('pos.index');
    Route::get('/sales-history', function () { return view('pos.sale_history'); })->name('sales.history');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
