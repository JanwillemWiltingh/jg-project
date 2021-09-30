<?php

use App\Http\Controllers\{LoginController};
use App\Http\Controllers\Users\{DashboardController, RoosterController};
use App\Http\Controllers\Admin\{ClockController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::name('auth.')->prefix('auth/')->group(function (){
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::name('dashboard.')->group(function (){
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::post('/clocker', [DashboardController::class, 'clock'])->name('clock');
});

Route::name('rooster.')->prefix('rooster/')->group(function (){
    Route::get('/', [RoosterController::class, 'index'])->name('index');
});

Route::name('admin.')->prefix('admin/')->group(function (){
    Route::name('clock.')->prefix('clock/')->group(function (){
        Route::get('/', [ClockController::class, 'index'])->name('index');
    });

    Route::name('users.')->prefix('users/')->group(function (){
        Route::get('/', [App\Http\Controllers\Admin\UserController::class,'index'])->name('index');
        Route::get('/show', [App\Http\Controllers\Admin\UserController::class,'show'])->name('show');
        Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
        Route::get('/store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        Route::get('/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::get('/update', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::get('/destroy/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
    });
});

