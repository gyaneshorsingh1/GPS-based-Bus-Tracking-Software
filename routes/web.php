    <?php

    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\SchoolController;
    use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
        return view('welcome');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware('verified', 'permission:dashboard.view')->name('dashboard');

        Route::get('/schools', [SchoolController::class, 'index'])->middleware('permission:school.view')->name('schools.index');
        Route::get('/schools/create', [SchoolController::class, 'create'])->middleware('permission:school.create')->name('schools.create');
        Route::post('/schools', [SchoolController::class, 'store'])->middleware('permission:school.create')->name('schools.store');
        Route::get('/schools/{school}', [SchoolController::class, 'show'])->middleware('permission:school.view')->name('schools.show');
        Route::get('/schools/{school}/edit', [SchoolController::class, 'edit'])->middleware('permission:school.update')->name('schools.edit');
        Route::put('/schools/{school}', [SchoolController::class, 'update'])->middleware('permission:school.update')->name('schools.update');
        Route::delete('/schools/{school}', [SchoolController::class, 'destroy'])->middleware('permission:school.delete')->name('schools.destroy');

        Route::get('/buttons', function () {
            return view('buttons');
        })->name('buttons');

        Route::get('/images', function () {
            return view('images');
        })->name('images');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__.'/auth.php';
