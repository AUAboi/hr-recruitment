<?php

use App\Http\Controllers\CVExtractorController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\JobBoardController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Models\JobListing;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/recruiter/dashboard', function () {
    return Inertia::render('Recruiter/Dashboard');
})->middleware(['auth', 'verified'])->name('recruiter.dashboard');


Route::get('/jobs', [JobBoardController::class, 'index'])->name('public.jobs');

Route::middleware('auth')->prefix('/recruiter')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('recruiter.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('recruiter.profile.update');
    Route::put('/profile/image', [ProfileController::class, 'updateImage'])->name('recruiter.profile.updateImage');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('recruiter.profile.destroy');

    Route::get('/cv-extractor', [CVExtractorController::class, 'index'])->name('recruiter.extractor');

    Route::get('/cv-evaluation/show/{evaluation}', [EvaluationController::class, 'show'])->name('recruiter.evaluation.show');

    Route::get('/cv-evaluation/create', [EvaluationController::class, 'create'])->name('recruiter.evaluation.create');
    Route::post('/cv-evaluation/store', [EvaluationController::class, 'store'])->name('recruiter.evaluation.store');

    Route::get('/cv-export', [EvaluationController::class, 'export'])->name('recruiter.evaluation.export');

    Route::get('/job/all', [JobListingController::class, 'index'])->name('recruiter.job.index');

    Route::get('/job/create', [JobListingController::class, 'create'])->name('recruiter.job.create');
    Route::post('/job/store', [JobListingController::class, 'store'])->name('recruiter.job.store');

    Route::get('/job/{job_listing}/edit', [JobListingController::class, 'edit'])->name('recruiter.job.edit');
    Route::patch('/job/{job_listing}/update', [JobListingController::class, 'update'])->name('recruiter.job.update');
});

Route::get('/role-login', [RoleController::class, 'index'])->name('role-login')->middleware(['auth']);

require __DIR__ . '/auth.php';
