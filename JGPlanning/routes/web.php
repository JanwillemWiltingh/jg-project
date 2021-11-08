<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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
    Route::get('/{week}', [RoosterController::class, 'index'])->name('index');
    Route::post('/availability/{week}', [RoosterController::class, 'add_availability'])->name('availability');
    Route::post('/availability-edit/{week}', [RoosterController::class, 'edit_availability'])->name('edit_availability');
    Route::get('/{user}/rooster-delete/{weekday}/{week}', [RoosterController::class, 'delete_rooster'])->name('delete_rooster');
});

//admin
Route::name('admin.')->prefix('admin/')->group(function (){
    Route::name('clock.')->prefix('clock/')->group(function (){
        Route::get('/', [ClockController::class, 'index'])->name('index');
        Route::get('/show/{clock}', [ClockController::class, 'show'])->name('show');
        Route::get('/edit/{clock}', [ClockController::class, 'edit'])->name('edit');
        Route::get('/update/{clock}', [ClockController::class, 'update'])->name('update');
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
        Route::get('/{user}/{week}', [RoosterAdminController::class, 'user_rooster'])->name('user_rooster');
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
    });
});

//password reset
Route::get('/forgot-password', function () {
    return view('Auth.forgot_password');
})->middleware('guest')->name('password.request');

//Route::post('/forgot-password', function (Request $request) {
//    $request->validate(['email' => 'required|email']);
//
//    $status = Password::sendResetLink(
//        $request->only('email')
//    );
//
//    return $status === Password::RESET_LINK_SENT
//        ? back()->with(['status' => __($status)])
//        : back()->withErrors(['email' => __($status)]);
//})->middleware('guest')->name('password.email');
//
//Route::get('/reset-password/{token}', function ($token) {
//    return view('auth.reset-password', ['token' => $token]);
//})->middleware('guest')->name('password.reset');
//Route::post('/reset-password', function (Request $request) {
//    $request->validate([
//        'token' => 'required',
//        'email' => 'required|email',
//        'password' => 'required|min:8|confirmed',
//    ]);
//
//    $status = Password::reset(
//        $request->only('email', 'password', 'password_confirmation', 'token'),
//        function ($user, $password) {
//            $user->forceFill([
//                'password' => Hash::make($password)
//            ])->setRememberToken(Str::random(60));
//
//            $user->save();
//
//            event(new PasswordReset($user));
//        }
//    );
//
//    return $status === Password::PASSWORD_RESET
//        ? redirect()->route('login')->with('status', __($status))
//        : back()->withErrors(['email' => [__($status)]]);
//})->middleware('guest')->name('password.update');
