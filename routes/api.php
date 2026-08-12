<?php

use App\Http\Controllers\Api\V1\Auth\ApiAuthController;
use App\Http\Controllers\Api\V1\Driver\DriverAttendanceController;
use App\Http\Controllers\Api\V1\Driver\DriverBusController;
use App\Http\Controllers\Api\V1\Driver\DriverDashboardController;
use App\Http\Controllers\Api\V1\Driver\DriverLiveTrackingController;
use App\Http\Controllers\Api\V1\Driver\DriverProfileController;
use App\Http\Controllers\Api\V1\Parent\ParentBusController;
use App\Http\Controllers\Api\V1\Parent\ParentChildController;
use App\Http\Controllers\Api\V1\Parent\ParentDashboardController;
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
            Route::get('/live-tracking', [DriverLiveTrackingController::class, 'index']);

            Route::prefix('attendances')->group(function () {
                Route::get('/', [DriverAttendanceController::class, 'index']);
                Route::get('/history', [DriverAttendanceController::class, 'history']);
                Route::post('/mark', [DriverAttendanceController::class, 'markAttendance']);
            });
        });

        Route::prefix('parent')->middleware('role:Parent')->group(function () {

            Route::get('/dashboard', [ParentDashboardController::class, 'index']);
            Route::get('/profile', [ParentDashboardController::class, 'profile']);
            Route::get('/children', [ParentChildController::class, 'index']);
            Route::get('/children/{student}', [ParentChildController::class, 'show']);
            Route::get('/children/{student}/bus', [ParentBusController::class, 'show']);
        });
    });
});
