<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\catagory\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('GADGET-GEARS/categoryModel', [CategoryController::class, 'index'])->name('GADGET-GEARS.categoryModel');
Route::post('GADGET-GEARS/categoryModel', [CategoryController::class, 'store'])->name('GADGET-GEARS.categoryModel');

