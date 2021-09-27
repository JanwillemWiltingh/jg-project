<?php
use App\Http\Controllers\ {
    LoginController,
    DashboardController,
    RoosterController,
};
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

Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/home', [DashboardController::class, 'index'])->name('home');
Route::get('/rooster', [RoosterController::class, 'index'])->name('rooster');

Route::name('clocker.')->prefix('clock-in/')->group(function (){
    Route::get('/', [App\Http\Controllers\Users\ClockerController::class, 'index'])->name('index');
});
