<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckOutController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
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
Route::middleware('auth:api')->group(function () {
    // user profile routes
    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('/', 'index');
        Route::put('/update', 'update');
        Route::get('/orders', 'orders');
    });
    // order routes
    Route::controller(OrderController::class)->prefix('orders')->group(function () {
        Route::get('/', 'index');   
        Route::post('/store', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
    // user checkout routes
    Route::controller(CheckOutController::class)->prefix('checkout')->group(function () {
        Route::post('/place-order', 'placeOrder');
        Route::get('/payment-methods', 'paymentMethods');
    });
});



