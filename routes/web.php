<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\ParentProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolAdminController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Super Admin Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/systemadmin/dashboard', function () {
        return view('dashboard');
    })
        ->middleware([
            'verified',
            'permission:dashboard.view',
            'role:Super Admin',
        ])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Principal Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/principal/dashboard', function () {
        return view('principalDashboard');
    })
        ->middleware([
            'permission:dashboard.view',
            'role:School Admin',
        ])
        ->name('principal.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Driver Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/driver/dashboard', function () {
        return view('dashboard');
    })
        ->middleware([
            'permission:dashboard.view',
            'role:Driver',
        ])
        ->name('driver.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Parent Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/parent/dashboard', function () {
        return view('dashboard');
    })
        ->middleware([
            'permission:dashboard.view',
            'role:Parent',
        ])
        ->name('parent.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    */

    Route::get('/drivers', [DriverController::class, 'index'])
        ->middleware('permission:driver.view')
        ->name('drivers.index');

    Route::get('/drivers/create', [DriverController::class, 'create'])
        ->middleware('permission:driver.create')
        ->name('drivers.create');

    Route::post('/drivers', [DriverController::class, 'store'])
        ->middleware('permission:driver.create')
        ->name('drivers.store');

    Route::get('/drivers/{driver}', [DriverController::class, 'show'])
        ->middleware('permission:driver.view')
        ->name('drivers.show');

    Route::get('/drivers/{driver}/edit', [DriverController::class, 'edit'])
        ->middleware('permission:driver.update')
        ->name('drivers.edit');

    Route::put('/drivers/{driver}', [DriverController::class, 'update'])
        ->middleware('permission:driver.update')
        ->name('drivers.update');

    Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])
        ->middleware('permission:driver.delete')
        ->name('drivers.destroy');

    /*
    |--------------------------------------------------------------------------
    | Schools
    |--------------------------------------------------------------------------
    */

    Route::get('/schools', [SchoolController::class, 'index'])
        ->middleware('permission:school.view')
        ->name('schools.index');

    Route::get('/schools/create', [SchoolController::class, 'create'])
        ->middleware('permission:school.create')
        ->name('schools.create');

    Route::post('/schools', [SchoolController::class, 'store'])
        ->middleware('permission:school.create')
        ->name('schools.store');

    Route::get('/schools/{school}', [SchoolController::class, 'show'])
        ->middleware('permission:school.view')
        ->name('schools.show');

    Route::get('/schools/{school}/edit', [SchoolController::class, 'edit'])
        ->middleware('permission:school.update')
        ->name('schools.edit');

    Route::put('/schools/{school}', [SchoolController::class, 'update'])
        ->middleware('permission:school.update')
        ->name('schools.update');

    Route::delete('/schools/{school}', [SchoolController::class, 'destroy'])
        ->middleware('permission:school.delete')
        ->name('schools.destroy');

    /*
    |--------------------------------------------------------------------------
    | Parents
    |--------------------------------------------------------------------------
    */

    Route::get('/parents', [ParentProfileController::class, 'index'])
        ->middleware('permission:parent.view')
        ->name('parents.index');

    Route::get('/parents/create', [ParentProfileController::class, 'create'])
        ->middleware('permission:parent.create')
        ->name('parents.create');

    Route::post('/parents', [ParentProfileController::class, 'store'])
        ->middleware('permission:parent.create')
        ->name('parents.store');

    Route::get('/parents/{parentProfile}', [ParentProfileController::class, 'show'])
        ->middleware('permission:parent.view')
        ->name('parents.show');

    Route::get('/parents/{parentProfile}/edit', [ParentProfileController::class, 'edit'])
        ->middleware('permission:parent.update')
        ->name('parents.edit');

    Route::put('/parents/{parentProfile}', [ParentProfileController::class, 'update'])
        ->middleware('permission:parent.update')
        ->name('parents.update');

    Route::delete('/parents/{parentProfile}', [ParentProfileController::class, 'destroy'])
        ->middleware('permission:parent.delete')
        ->name('parents.destroy');

    /*
    |--------------------------------------------------------------------------
    | School Admins
    |--------------------------------------------------------------------------
    */

    Route::get('/school-admins', [SchoolAdminController::class, 'index'])
        ->middleware('permission:school-admin.view')
        ->name('school-admins.index');

    Route::get('/school-admins/create', [SchoolAdminController::class, 'create'])
        ->middleware('permission:school-admin.create')
        ->name('school-admins.create');

    Route::post('/school-admins', [SchoolAdminController::class, 'store'])
        ->middleware('permission:school-admin.create')
        ->name('school-admins.store');

    Route::get('/school-admins/{schoolAdmin}', [SchoolAdminController::class, 'show'])
        ->middleware('permission:school-admin.view')
        ->name('school-admins.show');

    Route::get('/school-admins/{schoolAdmin}/edit', [SchoolAdminController::class, 'edit'])
        ->middleware('permission:school-admin.update')
        ->name('school-admins.edit');

    Route::put('/school-admins/{schoolAdmin}', [SchoolAdminController::class, 'update'])
        ->middleware('permission:school-admin.update')
        ->name('school-admins.update');

    Route::delete('/school-admins/{schoolAdmin}', [SchoolAdminController::class, 'destroy'])
        ->middleware('permission:school-admin.delete')
        ->name('school-admins.destroy');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:profile.view')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:profile.update')
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->middleware('permission:profile.update')
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Temporary UI Test Pages
    |--------------------------------------------------------------------------
    */

    Route::get('/buttons', function () {
        return view('buttons');
    })->name('buttons');

    Route::get('/images', function () {
        return view('images');
    })->name('images');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
