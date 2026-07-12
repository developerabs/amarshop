<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductDetailsController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SiteSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegistrationController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');
});
Route::controller(ProfileController::class)->middleware('auth:api')->prefix('profile')->group(function () {
    Route::get('/', 'index');
    Route::put('/update', 'update');
});

Route::controller(SiteSettingsController::class)->prefix('site-settings')->group(function () {
    Route::get('/', 'index');
});
Route::controller(HomeController::class)->prefix('home')->group(function () {
    Route::get('/products', 'homeProducts');
});
Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::get('/all-products', 'allProducts');
    Route::get('/{id}', 'getProductById');
    Route::get('/details/{slug}', 'details');
});
Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('/', 'index');
});
Route::controller(BrandController::class)->prefix('brands')->group(function () {
    Route::get('/', 'index');
});

