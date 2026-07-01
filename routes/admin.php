<?php

use Illuminate\Support\Facades\Route;
use App\Models\Admin\DashboardController;

Route::prefix('admin*')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});
Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index');
});
Route::group(['prefix' => 'users'], function () {
    Route::get('/', function () {
        return 'Admin Users List';
    });
    Route::get('/{id}', function ($id) {
        return "Admin User Details for ID: $id";
    });
});