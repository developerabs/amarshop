<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductDetailsController;
use App\Http\Controllers\Api\SiteSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::controller(SiteSettingsController::class)->prefix('site-settings')->group(function () {
    Route::get('/', 'index');
});
Route::controller(HomeController::class)->prefix('home')->group(function () {
    Route::get('/products', 'homeProducts');
});
Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::get('/all-products', 'allProducts');
    Route::get('/details/{slug}', 'details');
});
Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('/', 'index');
});
Route::controller(BrandController::class)->prefix('brands')->group(function () {
    Route::get('/', 'index');
});

