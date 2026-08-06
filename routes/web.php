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
use App\Http\Controllers\Web\SchoolController;
use App\Http\Controllers\Web\HotlineController;
use App\Http\Controllers\Web\WeatherAlertController;
use App\Http\Controllers\Web\SurveyController;
use App\Http\Controllers\Web\NewsCategoryController;
use App\Http\Controllers\Web\NewsController;
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

    Route::get('/',                 [DashboardController::class,  'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class,  'export'])->name('dashboard.export');
    Route::get('/appointments',     [AppointmentController::class,'index'])->name('appointments');
    Route::post('/appointments',    [AppointmentController::class,'store'])->name('appointments.store');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class,'updateStatus'])->name('appointments.updateStatus');
    Route::delete('/appointments/{appointment}',[AppointmentController::class,'destroy'])->name('appointments.destroy');
    Route::get('/profiles',         [ProfileController::class,    'index'])->name('profiles');
    Route::get('/profiles/export',  [ProfileController::class,    'export'])->name('profiles.export');
    Route::get('/reports',          [ReportController::class,     'index'])->name('reports');
    Route::get('/reports/export',   [ReportController::class,     'export'])->name('reports.export');
    Route::put('/reports/{id}/status', [ReportController::class,  'updateStatus'])->name('reports.updateStatus');
    Route::get('/notifications',    [NotificationController::class,'index'])->name('notifications');
    Route::get('/documents',        [DocumentController::class,   'index'])->name('documents');
    Route::get('/citizens',         [CitizenController::class,    'index'])->name('citizens');
    Route::get('/citizens/export',  [CitizenController::class,    'export'])->name('citizens.export');
    Route::get('/digitalmaps',      [DigitalmapController::class, 'index'])->name('digitalmaps');
    Route::resource('schools', SchoolController::class);
    Route::resource('hotlines', HotlineController::class);
    Route::resource('weather-alerts', WeatherAlertController::class);
    Route::resource('surveys', SurveyController::class);
    Route::resource('news-categories', NewsCategoryController::class);
    Route::resource('news', NewsController::class);
});