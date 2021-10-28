<?php

use App\Http\Controllers\{LoginController};
use App\Http\Controllers\Users\{DashboardController, HelpController, RoosterController, ProfileController};
use App\Http\Controllers\Admin\{UserController,ClockController, RoosterAdminController, CompareController};
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

Route::name('help.')->prefix('help/')->group(function (){
    Route::get('/', [HelpController::class, 'help'])->name('index');
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
    Route::get('/{week}', [RoosterController::class, 'index'])->name('index');
});

//TODO: nog onder rooster/* brengen maar ik ben er nog te bang voor aangezien ik nog bezig ben met inplannen voor de toekomst.
Route::post('/availability/{week}', [RoosterController::class, 'add_availability'])->name('availability');
Route::post('/availability-edit/{week}', [RoosterController::class, 'edit_availability'])->name('edit_availability');
Route::get('/{user}/rooster-delete/{weekday}/{week}', [RoosterController::class, 'delete_rooster'])->name('delete_rooster');

Route::name('admin.')->prefix('admin/')->group(function (){
    Route::name('clock.')->prefix('clock/')->group(function (){
        Route::get('/', [ClockController::class, 'index'])->name('index');
        Route::get('/show/{clock}', [ClockController::class, 'show'])->name('show');
        Route::get('/edit/{user}', [ClockController::class, 'edit'])->name('edit');
        Route::get('/update/{user}', [ClockController::class, 'update'])->name('update');
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
        Route::get('/', [RoosterAdminController::class, 'index_rooster'])->name('index');
        Route::get('/{user}/{week}', [RoosterAdminController::class, 'user_rooster'])->name('user_rooster');
        Route::post('/{user}/{week}/available_days', [RoosterAdminController::class, 'push_days'])->name('push_days');
        Route::post('/{user}/disable_days', [RoosterAdminController::class, 'disable_days'])->name('disable_days');
        Route::post('/{user}/{week}/edit_disable_days', [RoosterAdminController::class, 'edit_disable_days'])->name('edit_disable_days');
    });

    Route::name('compare.')->prefix('vergelijken/')->middleware('ensure.admin')->group(function (){
        Route::get('/', [CompareController::class, 'index'])->name('index');
    });
});

