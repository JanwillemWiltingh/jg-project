<?php

use App\Http\Controllers\{LoginController};
use App\Http\Controllers\Users\{DashboardController, RoosterController};
use App\Http\Controllers\Admin\{UserController,ClockController};
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

Route::get('/availability', [RoosterController::class, 'add_availability'])->name('availability');
Route::post('/availability-edit', [RoosterController::class, 'edit_availability'])->name('edit_availability');

Route::name('admin.')->prefix('admin/')->group(function (){
    Route::name('clock.')->prefix('clock/')->group(function (){
        Route::get('/', [ClockController::class, 'index'])->name('index');
    });

    Route::name('users.')->prefix('users/')->middleware('ensure.admin')->group(function (){
        Route::get('/', [UserController::class,'index'])->name('index');
        Route::get('/show', [UserController::class,'show'])->name('show');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit', [UserController::class, 'edit'])->name('edit');
        Route::get('/update', [UserController::class, 'update'])->name('update');
        Route::get('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});

