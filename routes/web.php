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
Route::get('migrate', function () {
    Artisan::call('migrate');
});
Route::get('key', function () {
    Artisan::call('key:generate');
});
Route::get('passport-key', function () {
    Artisan::call('passport:keys --force');
});
Route::get('composer-dump-autoload', function () {
     $output = [];
    $return = 0;

    exec('composer dump-autoload 2>&1', $output, $return);

    return response()->json([
        'status' => $return,
        'output' => $output,
    ]);
});
Route::get('compose-update', function () {
    $output = [];
    $return = 0;

    exec('composer update 2>&1', $output, $return);

    return response()->json([
        'status' => $return,
        'output' => $output,
    ]);
});

Route::get('cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cache is cleared";
});
