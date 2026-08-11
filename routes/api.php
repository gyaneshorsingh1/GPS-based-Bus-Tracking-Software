<?php

use App\Http\Controllers\Api\V1\Auth\ApiAuthController;
use App\Http\Controllers\Api\V1\Driver\DriverAttendanceController;
use App\Http\Controllers\Api\V1\Driver\DriverBusController;
use App\Http\Controllers\Api\V1\Driver\DriverDashboardController;
use App\Http\Controllers\Api\V1\Driver\DriverProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public
    Route::post('/auth/login', [ApiAuthController::class, 'login']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [ApiAuthController::class, 'me']);
        Route::post('/auth/logout', [ApiAuthController::class, 'logout']);

        Route::prefix('driver')->group(function () {
            Route::get('/dashboard', [DriverDashboardController::class, 'index']);
            Route::get('/profile', [DriverProfileController::class, 'show']);
            Route::get('/buses', [DriverBusController::class, 'index']);
            Route::get('/buses/{bus}', [DriverBusController::class, 'show']);
            Route::get('/buses/{bus}/students', [DriverBusController::class, 'students']);

        Route::prefix('attendances')->group(function () {
                Route::get('/', [DriverAttendanceController::class, 'index']);
                Route::post('/mark', [DriverAttendanceController::class, 'markAttendance']);
            });
        });
    });
});
