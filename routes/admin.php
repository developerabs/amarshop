<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SiteSettingsController;
use Illuminate\Support\Facades\Route;


// Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware('admin');

Route::name('admin.')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    // site settings route
    Route::controller(SiteSettingsController::class)->group(function () {
        Route::get('/site-settings', 'index')->name('site-settings');
        Route::post('/site-settings', 'update')->name('site-settings.update');
    });
    // category management
    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{category}', 'destroy')->name('destroy');
    });
    // brand management
    Route::controller(BrandController::class)->prefix('brands')->name('brands.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{brand}', 'destroy')->name('destroy');
    });
    // product management
    Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/edit/{product}', 'edit')->name('edit');
        Route::put('/update/{product}', 'update')->name('update');
        Route::delete('/{product}', 'destroy')->name('destroy');
    });
    // order management
    Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order}', 'show')->name('show');
        Route::get('/active', 'active')->name('active');
        Route::get('/pending', 'pending')->name('pending');
        Route::get('/completed', 'completed')->name('completed');
        Route::get('/cancelled', 'cancelled')->name('cancelled');
        Route::put('/update-status/{order}', 'updateStatus')->name('update-status');
        Route::delete('/{order}', 'destroy')->name('destroy');
        Route::get('details/{order}', 'details')->name('details');
    });
});   