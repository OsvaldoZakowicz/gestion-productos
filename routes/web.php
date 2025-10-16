<?php

use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('components.layout');
});

// brands resource
Route::resource('brands', BrandController::class);

// categories resource
Route::resource('categories', CategoryController::class);

// products resource
Route::resource('products', ProductController::class);