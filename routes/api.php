<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\UserController;
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
