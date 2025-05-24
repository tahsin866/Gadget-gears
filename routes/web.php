<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\catagory\CategoryController;
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


  Route::get('/products', function () {
        return Inertia::render('GADGET-GEARS/product');
    })->name('GADGET-GEARS.product');

    Route::get('/categories', function () {
        return Inertia::render('GADGET-GEARS/category');
    })->name('GADGET-GEARS.category');

    Route::get('/orders', function () {
        return Inertia::render('Orders/Index');
    })->name('orders.index');

    Route::get('/customers', function () {
        return Inertia::render('Customers/Index');
    })->name('customers.index');

    Route::get('/analytics', function () {
        return Inertia::render('Analytics/Index');
    })->name('analytics.index');

    Route::get('/inventory', function () {
        return Inertia::render('Inventory/Index');
    })->name('inventory.index');

    Route::get('/settings', function () {
        return Inertia::render('Settings/Index');
    })->name('settings.index');






Route::get('GADGET-GEARS/categoryModel', [CategoryController::class, 'index'])->name('GADGET-GEARS.categoryModel');
Route::post('GADGET-GEARS/categoryModel', [CategoryController::class, 'store'])->name('GADGET-GEARS.categoryModel');














require __DIR__.'/auth.php';
