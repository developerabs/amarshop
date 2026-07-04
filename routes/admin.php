<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\VariantTypesController;
use App\Http\Controllers\Admin\VariantValuesController;
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
    // variant type management
    Route::controller(VariantTypesController::class)->prefix('variant-types')->name('variant-types.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{variantType}', 'destroy')->name('destroy');
    });
    // variant value management
    Route::controller(VariantValuesController::class)->prefix('variant-values')->name('variant-values.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{variantValue}', 'destroy')->name('destroy');
    });
    // attribute management
    Route::controller(AttributeController::class)->prefix('attributes')->name('attributes.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{attribute}', 'destroy')->name('destroy');
    });
    Route::controller(AttributeValueController::class)->prefix('attribute-values')->name('attribute-values.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'storeValue')->name('store');
        Route::put('/update', 'updateValue')->name('update');
        Route::delete('/{attributeValue}', 'destroyValue')->name('destroy');
    });
});