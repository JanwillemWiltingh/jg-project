<?php

use App\Http\Controllers\{LoginController};
use App\Http\Controllers\Users\{DashboardController, RoosterController};
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

