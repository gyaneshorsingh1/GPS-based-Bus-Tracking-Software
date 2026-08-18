<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\V1\Auth\ApiAuthController;
use App\Http\Controllers\Api\V1\Driver\DriverAttendanceController;
use App\Http\Controllers\Api\V1\Driver\DriverBusController;
use App\Http\Controllers\Api\V1\Driver\DriverDashboardController;
use App\Http\Controllers\Api\V1\Driver\DriverLiveTrackingController;
use App\Http\Controllers\Api\V1\Driver\DriverProfileController;
use App\Http\Controllers\Api\V1\Parent\ParentBusController;
use App\Http\Controllers\Api\V1\Parent\ParentChildController;
use App\Http\Controllers\Api\V1\Parent\ParentDashboardController;
use App\Http\Controllers\Api\V1\Parent\ParentLiveTrackingController;
use App\Http\Controllers\Api\V1\Principal\PrincipalBusController;
use App\Http\Controllers\Api\V1\Principal\PrincipalDashboardController;
use App\Http\Controllers\Api\V1\Principal\PrincipalDriverController;
use App\Http\Controllers\Api\V1\Principal\PrincipalLiveTrackingController;
use App\Http\Controllers\Api\V1\Principal\PrincipalRouteController;
use App\Http\Controllers\Api\V1\Principal\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Public endpoints
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Authenticated endpoints
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    /*
    |--------------------------------------------------------------------------
    | User Management (Super Admin only via the `manage-users` gate)
    |--------------------------------------------------------------------------
    */

    Route::prefix('users')
        ->middleware('can:manage-users')
        ->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/{user}', [UserController::class, 'show']);
            Route::put('/{user}', [UserController::class, 'update']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
        });

    /*
    |--------------------------------------------------------------------------
    | Parent Mobile App (Parent role only via the `access-parent-api` gate)
    |--------------------------------------------------------------------------
    |
    | Every endpoint resolves resources through the authenticated parent's own
    | ParentProfile, so a parent can only ever see their own profile, children,
    | and the bus/route/tracking/trip data linked to those children.
    */

    Route::prefix('parent')
        ->middleware('can:access-parent-api')
        ->group(function () {
            Route::get('/profile', [ParentController::class, 'profile']);
            Route::get('/students', [ParentController::class, 'students']);
            Route::get('/students/{student}', [ParentController::class, 'showStudent']);
            Route::get('/students/{student}/bus', [ParentController::class, 'studentBus']);
            Route::get('/students/{student}/route', [ParentController::class, 'studentRoute']);
            Route::get('/students/{student}/tracking', [ParentController::class, 'studentTracking']);
            Route::get('/students/{student}/trip', [ParentController::class, 'studentTrip']);
            Route::get('/notifications', [ParentController::class, 'notifications']);
            Route::get('/notifications/unread-count', [ParentController::class, 'unreadNotificationsCount']);
            Route::post('/notifications/{id}/read', [ParentController::class, 'markNotificationAsRead']);
            Route::post('/notifications/read-all', [ParentController::class, 'markAllNotificationsAsRead']);
        });
});

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
            Route::get('/children/{student}/history', [ParentChildController::class, 'history']);
            Route::get('/children/{student}/bus', [ParentBusController::class, 'show']);
            Route::get('/children/{student}/live-tracking', [ParentLiveTrackingController::class, 'show']);
            Route::get('/live-tracking', [ParentLiveTrackingController::class, 'index']);
        });

        Route::prefix('principal')->middleware(['role:School Admin', 'permission:dashboard.view'])->group(function () {
            Route::get('/dashboard', [PrincipalDashboardController::class, 'index']);
            Route::get('/profile', [PrincipalDashboardController::class, 'profile']);
        });

        Route::prefix('principal')->middleware('role:School Admin')->group(function () {
            Route::get('/buses', [PrincipalBusController::class, 'index'])->middleware('permission:bus.view');
            Route::get('/buses/{bus}', [PrincipalBusController::class, 'show'])->middleware('permission:bus.view');

            Route::get('/drivers', [PrincipalDriverController::class, 'index'])->middleware('permission:driver.view');
            Route::get('/drivers/{driver}', [PrincipalDriverController::class, 'show'])->middleware('permission:driver.view');

            Route::get('/routes', [PrincipalRouteController::class, 'index'])->middleware('permission:route.view');
            Route::get('/routes/{route}', [PrincipalRouteController::class, 'show'])->middleware('permission:route.view');

            Route::get('/live-tracking', [PrincipalLiveTrackingController::class, 'index'])->middleware('permission:gps.view');
        });

        Route::middleware('role:School Admin')->prefix('students')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->middleware('permission:student.view');
            Route::post('/', [StudentController::class, 'store'])->middleware('permission:student.create');
            Route::get('/{student}', [StudentController::class, 'show'])->middleware('permission:student.view');
            Route::put('/{student}', [StudentController::class, 'update'])->middleware('permission:student.update');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->middleware('permission:student.delete');
        });
    });
});
