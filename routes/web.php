<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// route for admin

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => 'auth'], function () {
        //route dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.index');

        //route category
        Route::resource('/category', \App\Http\Controllers\Admin\CategoryController::class, ['as' => 'admin']);

        //route product
        Route::resource('/product', \App\Http\Controllers\Admin\ProductController::class, ['as' => 'admin']);

        //route order
        Route::resource('/order', \App\Http\Controllers\Admin\OrderController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'admin']);

        //route customer
        Route::get('/customer', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customer.index');

        //route slider
        //Route::resource('/slider', \App\Http\Controllers\Admin\SliderController::class,
        //    ['except' => ['show', 'create', 'edit', 'update'], 'as' => 'admin']);

        //route profile
        //Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('admin.profile.index');

        //route user
        //Route::resource('/user', \App\Http\Controllers\Admin\UserController::class, ['except' => ['show'], 'as' => 'admin']);
    });
});