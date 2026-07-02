<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SiteSettingsController;


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

});