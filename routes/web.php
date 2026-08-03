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

        Route::get('/schools', [SchoolController::class, 'index'])->middleware('verified')->name('schools.index');

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
