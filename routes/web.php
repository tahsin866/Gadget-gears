<?php

use App\Http\Controllers\addresController;
use App\Http\Controllers\profileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\catagory\CategoryController;
use App\Http\Controllers\productController;
use App\Http\Controllers\brandController;
use App\Http\Controllers\GadgetGearsController;


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

    // Product Management Routes
    Route::delete('/admin/products/{id}', [GadgetGearsController::class, 'deleteProduct'])
        ->name('admin.products.delete');

    Route::post('/admin/products/bulk-delete', [GadgetGearsController::class, 'bulkDeleteProducts'])
        ->name('admin.products.bulk-delete');

    Route::post('/admin/products/{id}/duplicate', [GadgetGearsController::class, 'duplicateProduct'])
        ->name('admin.products.duplicate');

    Route::patch('/admin/products/{id}/toggle-featured', [GadgetGearsController::class, 'toggleFeatured'])
        ->name('admin.products.toggle-featured');

    // Order Management Routes
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index']);
    Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show']);
    Route::patch('/orders/{id}/status', [App\Http\Controllers\OrderController::class, 'updateStatus']);
    Route::delete('/orders/{id}', [App\Http\Controllers\OrderController::class, 'destroy']);

    // Orders Page Route (Protected)
    Route::get('/Orders', function () {
        return Inertia::render('GADGET-GEARS/Orders');
    })->name('GADGET-GEARS.Orders');

    // Brand Management Routes (Protected)
    Route::put('/brand/{id}', [brandController::class, 'updateBrand']);
    Route::delete('/brand/{id}', [brandController::class, 'deleteBrand']);
});


  Route::get('/products', function () {
        return Inertia::render('GADGET-GEARS/product');
    })->name('GADGET-GEARS.product');

    Route::get('/categories', function () {
        return Inertia::render('GADGET-GEARS/category');
    })->name('GADGET-GEARS.category');

        Route::get('/brand', function () {
        return Inertia::render('GADGET-GEARS/brand');
    })->name('GADGET-GEARS.brand');


        Route::get('/settings', function () {
        return Inertia::render('GADGET-GEARS/settings');
    })->name('GADGET-GEARS.settings');



    Route::get('/createProduct', function () {
        return Inertia::render('GADGET-GEARS/createProduct');
    })->name('GADGET-GEARS.createProduct');



 Route::get('/buyeProduct', function () {
        return Inertia::render('GADGET-GEARS/buyeProduct');
    })->name('GADGET-GEARS.buyeProduct');



    Route::get('/customers', function () {
        return Inertia::render('Customers/Index');
    })->name('customers.index');

    Route::get('/analytics', function () {
        return Inertia::render('Analytics/Index');
    })->name('analytics.index');

    Route::get('/inventory', function () {
        return Inertia::render('Inventory/Index');
    })->name('inventory.index');

    // Route::get('/settings', function () {
    //     return Inertia::render('Settings/Index');
    // })->name('settings.index');

Route::get('/order-form', function () {
    return Inertia::render('GADGET-GEARS/orderForm', [
        'product' => request('product'),
        'quantity' => request('quantity', 1)
    ]);
})->name('GADGET-GEARS.orderForm');





Route::get('/buyeProduct/{id}', [GadgetGearsController::class, 'buyeProduct'])
    ->name('GADGET-GEARS.buyeProduct');

Route::get('/order-form', [GadgetGearsController::class, 'orderForm'])
    ->name('GADGET-GEARS.orderForm');



Route::post('/categories', [CategoryController::class, 'InsertCategory']);

Route::post('/parent-categories', [CategoryController::class, 'InsertParentCategory']);

Route::get('/show-cetagory', [CategoryController::class , 'showParentCetagory']);
Route::get('/show-SubCetagory', [CategoryController::class , 'showCetagory']);

Route::get('/show-cetagory-list', [CategoryController::class , 'showCetagoryList']);
Route::delete('/delete-category/{id}', [CategoryController::class, 'deleteCetagory']);



// existing routes এর সাথে এই route টি যোগ করো

Route::post('/products', [productController::class, 'store'])->name('products.store');
Route::get('/products-list', [productController::class, 'productList'])->name('products.List');
Route::post('/brand', [brandController::class, 'insertBrand']);
Route::get('/show-brand', [brandController::class, 'ShowBrand']);
Route::get('/products-grid', [productController::class, 'showGrid']);
Route::get('/brands', [brandController::class, 'showBrands']);
Route::get('/division', [addresController::class, 'ShowDivision']);
Route::get('/get-categories', [CategoryController::class, 'getCategories']);
Route::get('/feature-types', [productController::class, 'getFeatureTypes']);

// Public Order Routes (for guest orders)
Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store']);

Route::get('/divisions', [\App\Http\Controllers\addresController::class, 'ShowDivision']);
Route::get('/districts/{divisionId}', [\App\Http\Controllers\addresController::class, 'ShowDistrict']);
Route::get('/thanas/{desId}', [\App\Http\Controllers\addresController::class, 'ShowThana']);


Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

// Debug route to check Google config
Route::get('/debug-google', function() {
    return response()->json([
        'client_id' => config('services.google.client_id') ? 'Set' : 'Not Set',
        'client_secret' => config('services.google.client_secret') ? 'Set' : 'Not Set',
        'redirect_uri' => config('services.google.redirect'),
        'app_url' => config('app.url')
    ]);
});

require __DIR__.'/auth.php';
