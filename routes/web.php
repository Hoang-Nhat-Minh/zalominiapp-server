<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\AppointmentController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\DocumentController;
use App\Http\Controllers\Web\CitizenController;
use App\Http\Controllers\Web\DigitalmapController;
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

//Route::get('/', function () {
//    return view('welcome');
//});

/* ---------- Guest ---------- */
Route::middleware('guest:officer')->group(function () {
    Route::get('/login',  [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
});

/* ---------- Authenticated ---------- */
Route::middleware('auth:officer')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/',            [DashboardController::class,  'index'])->name('dashboard');
    Route::get('/appointments',[AppointmentController::class,'index'])->name('appointments');
    Route::get('/profiles',    [ProfileController::class,    'index'])->name('profiles');
    Route::get('/reports',     [ReportController::class,     'index'])->name('reports');
    Route::get('/notifications',[NotificationController::class,'index'])->name('notifications');
    Route::get('/documents',   [DocumentController::class,   'index'])->name('documents');
    Route::get('/citizens',    [CitizenController::class,    'index'])->name('citizens');
    Route::get('/digitalmaps', [DigitalmapController::class, 'index'])->name('digitalmaps');
});