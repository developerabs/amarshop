<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
})->middleware('admin');
Route::get('storagelink', function () {
    Artisan::call('storage:link');
});
