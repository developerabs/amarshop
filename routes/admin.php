<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShippingChargeController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserCareController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    // site settings route
    Route::controller(SiteSettingsController::class)->prefix('settings')->name('settings.')->group(function () {
        Route::get('/general-settings', 'generalSettings')->name('general-settings');
        Route::put('/general-settings', 'generalSettingsUpdate')->name('general-settings.update');
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
        Route::post('/', 'search')->name('search');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/edit/{product}', 'edit')->name('edit');
        Route::put('/update/{product}', 'update')->name('update');
        Route::delete('/{product}', 'destroy')->name('destroy');
    });
    // order management
    Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::get('/active', 'active')->name('active');
        Route::get('/pending', 'pending')->name('pending');
        Route::get('/completed', 'completed')->name('completed');
        Route::get('/cancelled', 'cancelled')->name('cancelled');
        Route::put('/update-status/{order}', 'updateStatus')->name('update-status');
        Route::put('/update-payment-status/{order}', 'updatePaymentStatus')->name('update-payment-status');
        Route::delete('/{order}', 'destroy')->name('destroy');
        Route::get('details/{order}', 'details')->name('details');
        Route::get('/{order}', 'show')->name('show');
    });
    // shipping charges management
    Route::controller(ShippingChargeController::class)->prefix('shipping-charges')->name('shipping-charges.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{shippingCharge}', 'destroy')->name('destroy');
        Route::post('/update-free-shipping-amount', 'updateFreeShippingAmount')->name('update-free-shipping-amount');
    });
    // slider management
    Route::controller(SliderController::class)->prefix('sliders')->name('sliders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{slider}', 'destroy')->name('destroy');
    });
    // banner management
    Route::controller(BannerController::class)->prefix('banners')->name('banners.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{banner}', 'destroy')->name('destroy');
    });
    // page management
    Route::controller(PageController::class)->prefix('pages')->name('pages.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/{page}', 'destroy')->name('destroy');
    });
    // blog category management
    Route::controller(BlogCategoryController::class)->prefix('blog-categories')->name('blog-categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{blogCategory}', 'destroy')->name('destroy');
    });
    // blog management
    Route::controller(BlogController::class)->prefix('blogs')->name('blogs.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{blog}', 'destroy')->name('destroy');
    });
    // menu management
    Route::controller(MenuController::class)->prefix('menus')->name('menus.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{menu}', 'destroy')->name('destroy');
        // menu items management
        Route::get('/{menu}/items', 'getMenuItems')->name('items.index');
        Route::post('/{menu}/items', 'storeMenuItem')->name('items.store');
        Route::post('/{menu}/items/reorder', 'reorderMenuItems')->name('items.reorder');
        Route::put('/{menu}/items/{item}', 'updateMenuItem')->name('items.update');
        Route::delete('/{menu}/items/{item}', 'destroyMenuItem')->name('items.destroy');
    });
    // user management
    Route::controller(UserCareController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
        Route::post('/create', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
    });
});