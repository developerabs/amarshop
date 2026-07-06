<?php

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
Route::controller(ProductController::class)->prefix('product')->group(function () {
    Route::get('/flash-deals', 'flashDeals');
    Route::get('/details/{slug}', 'details');
});

