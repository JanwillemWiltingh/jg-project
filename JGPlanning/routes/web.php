<?php

use App\Http\Controllers\{LoginController};
use App\Http\Controllers\Users\{DashboardController, RoosterController, ProfileController};
use App\Http\Controllers\Admin\{UserController,ClockController, AvailabilityController, CompareController};
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

Route::name('profile.')->prefix('profiel/')->group(function (){
    Route::get('/', [ProfileController::class, 'profile'])->name('index');
    Route::get('/edit/{user}', [ProfileController::class, 'edit'])->name('edit');
    Route::get('/update/{user}', [ProfileController::class, 'update'])->name('update');
});

Route::name('beschikbaarheid.')->prefix('beschikbaarheid/')->group(function (){
    Route::get('/', [RoosterController::class, 'index'])->name('index');
});

Route::name('rooster.')->prefix('rooster/')->group(function (){
    Route::get('/', [RoosterController::class, 'show_rooster'])->name('index');
});

Route::post('/availability', [RoosterController::class, 'add_availability'])->name('availability');
Route::post('/availability-edit/', [RoosterController::class, 'edit_availability'])->name('edit_availability');
Route::get('/{user}/availability-delete/{weekday}', [RoosterController::class, 'delete_availability'])->name('delete_availability');
Route::get('/{user}/rooster-delete/{weekday}', [RoosterController::class, 'delete_rooster'])->name('delete_rooster');

Route::name('admin.')->prefix('admin/')->group(function (){
    Route::name('clock.')->prefix('clock/')->group(function (){
        Route::get('/', [ClockController::class, 'index'])->name('index');
        Route::get('/show/{clock}', [ClockController::class, 'show'])->name('show');
    });

    Route::name('users.')->prefix('users/')->middleware('ensure.admin')->group(function (){
        Route::get('/', [UserController::class,'index'])->name('index');
        Route::get('/show/{user}', [UserController::class,'show'])->name('show');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
        Route::get('/update/{user}', [UserController::class, 'update'])->name('update');
        Route::get('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::name('rooster.')->prefix('rooster/')->middleware('ensure.admin')->group(function (){
        Route::get('/', [AvailabilityController::class, 'index_rooster'])->name('index');
        Route::get('/{user}', [AvailabilityController::class, 'user_rooster'])->name('user_rooster');
        Route::post('/{user}/available_days', [RoosterController::class, 'push_days'])->name('push_days');
    });

    Route::name('compare.')->prefix('vergelijken/')->middleware('ensure.admin')->group(function (){
        Route::get('/', [CompareController::class, 'index'])->name('index');
    });
});

