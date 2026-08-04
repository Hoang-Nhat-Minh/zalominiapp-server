<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AiController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PartyController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\HotlineController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\SurveyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('log.api')->group(function () {
    // ==================== AUTH ====================
    Route::prefix('auth')->group(function () {
        Route::post('/zalo',    [AuthController::class, 'zalo']);
        Route::post('/send-otp', [AuthController::class, 'sendOtp']);
        Route::post('/otp',     [AuthController::class, 'verifyOtp']);
    });

    // ==================== AUTHENTICATED ====================
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::prefix('auth')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::put('/me', [AuthController::class, 'update']);
        });

        // Profiles
        Route::prefix('profiles')->group(function () {
            Route::get('/',          [ProfileController::class, 'index']);
            Route::get('/search',    [ProfileController::class, 'search']);
            Route::get('/{code}',    [ProfileController::class, 'show']);
        });

        // Appointments
        Route::prefix('appointments')->group(function () {
            Route::get('/',          [AppointmentController::class, 'index']);
            Route::post('/',         [AppointmentController::class, 'store']);
            Route::put('/{id}/cancel', [AppointmentController::class, 'cancel']);
        });

        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/',  [ReportController::class, 'index']);
            Route::post('/', [ReportController::class, 'store']);
        });

        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/',              [NotificationController::class, 'index']);
            Route::put('/{id}/read',     [NotificationController::class, 'read']);
            Route::put('/{id}/acknowledge', [NotificationController::class, 'acknowledge']);
            Route::put('/read-all',      [NotificationController::class, 'readAll']);
        });

        // Posts / News
        Route::prefix('posts')->group(function () {
            Route::get('/',           [PostController::class, 'index']);
            Route::get('/categories', [PostController::class, 'categories']);
            Route::get('/{id}',       [PostController::class, 'show']);
        });

        // Party
        Route::prefix('party')->group(function () {
            Route::get('/documents',           [PartyController::class, 'documents']);
            Route::get('/documents/{id}',      [PartyController::class, 'documentShow']);
            Route::get('/votes',               [PartyController::class, 'votes']);
            Route::get('/votes/{id}',          [PartyController::class, 'voteShow']);
            Route::post('/votes/{id}/submit',  [PartyController::class, 'voteSubmit']);
        });

        // AI Assistant
        Route::prefix('ai')->group(function () {
            Route::post('/chat',    [AiController::class, 'chat']);
            Route::get('/history',  [AiController::class, 'history']);
        });

        // Schools
        Route::prefix('schools')->group(function () {
            Route::get('/',          [SchoolController::class, 'index']);
            Route::get('/{id}',      [SchoolController::class, 'show']);
        });

        // Hotlines
        Route::prefix('hotlines')->group(function () {
            Route::get('/',          [HotlineController::class, 'index']);
        });

        // Weather
        Route::prefix('weather')->group(function () {
            Route::get('/',          [WeatherController::class, 'index']);
        });

        // Surveys
        Route::prefix('surveys')->group(function () {
            Route::get('/',             [SurveyController::class, 'index']);
            Route::get('/{id}',         [SurveyController::class, 'show']);
            Route::post('/{id}/submit', [SurveyController::class, 'submit']);
        });

        Route::post('/location/resolve', [ReportController::class, 'location']);
    });
});
