<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::get('/admin/login', 'showLoginForm')->name('admin.login');
    Route::post('/admin/login', 'login')->name('admin.login.submit');
    Route::post('/admin/logout', 'logout')->name('admin.logout')->middleware('admin');
});
// Route::get('/admin/login', function () {
//     return view('admin.auth.login');
// })->name('admin.login');