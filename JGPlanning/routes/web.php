<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Controllers\{Auth\ForgotPasswordController, Auth\ResetPasswordController, LoginController};
use App\Http\Controllers\Users\{DashboardController, HelpController, RoosterController, ProfileController};
use App\Http\Controllers\Admin\{UserController,ClockController, RoosterAdminController, CompareController};
use App\Http\Controllers\Users;
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
//login
Route::get('/login', [LoginController::class, 'index'])->name('login');

//auth login
Route::name('auth.')->prefix('auth/')->group(function (){
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

//dashboard
Route::name('dashboard.')->group(function (){
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::post('/clocker', [DashboardController::class, 'clock'])->name('clock');
});

//help
Route::name('help.')->prefix('help/')->group(function (){
    Route::get('/', [HelpController::class, 'help'])->name('index');
});

//profiel
Route::name('profile.')->prefix('profiel/')->group(function (){
    Route::get('/', [ProfileController::class, 'profile'])->name('index');
    Route::get('/edit/{user}', [ProfileController::class, 'edit'])->name('edit');
    Route::get('/update/{user}', [ProfileController::class, 'update'])->name('update');
});

Route::name('rooster.')->prefix('rooster/')->group(function (){
    Route::post('/manage_disable', [RoosterController::class, 'manage_disable_days'])->name('manage_disable_days');
    Route::post('/manage_day_disable', [RoosterController::class, 'manage_delete_days'])->name('manage_delete_days');
    Route::get('/{week}/{year}', [RoosterController::class, 'index'])->name('index');
    Route::post('/disable_days', [RoosterController::class, 'disable_days'])->name('disable_days');
    Route::get('/disable_days/{week}/{year}/{day}', [RoosterController::class, 'disable_days_click'])->name('disable_days_click');
    Route::post('/availability/{week}', [RoosterController::class, 'add_availability'])->name('availability');
    Route::post('/availability-edit/{week}/{year}', [RoosterController::class, 'edit_availability'])->name('edit_availability');
    Route::get('/{user}/rooster-delete/{weekday}/{week}', [RoosterController::class, 'delete_rooster'])->name('delete_rooster');
    Route::get('/disable-days-delete/{weekday}/{week}', [RoosterController::class, 'delete_disable'])->name('delete_disable');
    Route::post('/{week}/edit_disable_days', [RoosterController::class, 'edit_disable_days'])->name('edit_disable_days');
});

Route::name('user.')->prefix('gebruiker/')->group(function (){
    Route::name('clock.')->prefix('clock/')->group(function (){
        Route::get('/', [Users\ClockController::class, 'index'])->name('index');
    });
});

//admin
Route::name('admin.')->prefix('admin/')->group(function (){
    Route::name('clock.')->prefix('clock/')->group(function (){
        Route::get('/', [ClockController::class, 'index'])->name('index');
        Route::get('/show/{clock}', [ClockController::class, 'show'])->name('show');
        Route::get('/edit/{clock}', [ClockController::class, 'edit'])->name('edit');
        Route::get('/update/{clock}', [ClockController::class, 'update'])->name('update');
        Route::get('/destroy/{clock}', [ClockController::class, 'destroy'])->name('destroy');
    });

//admin users table
    Route::name('users.')->prefix('users/')->middleware('ensure.admin')->group(function (){
        Route::get('/', [UserController::class,'index'])->name('index');
        Route::get('/show/{user}', [UserController::class,'show'])->name('show');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
        Route::get('/update/{user}', [UserController::class, 'update'])->name('update');
        Route::get('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

//admin rooster table
    Route::name('rooster.')->prefix('rooster/')->middleware('ensure.admin')->group(function (){
        Route::get('/', [RoosterAdminController::class, 'index_rooster'])->name('index');
        Route::get('/{user}/{week}/{year}', [RoosterAdminController::class, 'user_rooster'])->name('user_rooster');
        Route::post('/{user}/{week}/available_days', [RoosterAdminController::class, 'push_days'])->name('push_days');
        Route::post('/{user}/disable_days', [RoosterAdminController::class, 'disable_days'])->name('disable_days');
        Route::post('/{user}/{week}/edit_disable_days', [RoosterAdminController::class, 'edit_disable_days'])->name('edit_disable_days');
        Route::get('/{user}/{week}/{weekday}', [RoosterAdminController::class, 'delete_disable_days'])->name('delete_disable_days');
        Route::post('/manage_disable', [RoosterAdminController::class, 'manage_disable_days'])->name('manage_disable_days');
        Route::post('/manage_day_disable', [RoosterAdminController::class, 'manage_delete_days'])->name('manage_delete_days');
    });

//admin compare table
    Route::name('compare.')->prefix('vergelijken/')->middleware('ensure.admin')->group(function (){
        Route::get('/', [CompareController::class, 'index'])->name('index');
        Route::get('/show/{user}/{type}/{time}', [CompareController::class, 'show'])->name('show');
    });
});
Route::name('help.')->prefix('help/')->group(function (){
    Route::get('/', [HelpController::class, 'help'])->name('index');
});

Route::get('forget-password', [ForgotPasswordController::class, 'getEmail'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'postEmail'])->name('forget.password.post');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'getPassword'])->name('reset.password.get');
Route::post('reset-password', [ResetPasswordController::class, 'updatePassword'])->name('reset.password.post');
