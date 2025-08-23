<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

use App\Http\Controllers\OrderController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/cart/add', [CartController::class, 'addToCart']);
Route::get('/cart/count', [CartController::class, 'getCartItemCount']);
Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store']);
