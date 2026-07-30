<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
})->middleware('admin');
Route::get('storagelink', function () {
    Artisan::call('storage:link');
});
Route::get('seed', function () {
    Artisan::call('migrate:fresh --seed');
});
Route::get('passport', function () {
    Artisan::call('passport:install --force');
});

Route::get('cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cache is cleared";
});
