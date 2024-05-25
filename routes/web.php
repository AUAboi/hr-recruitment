<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobBoardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\CVExtractorController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\ShowDesktopPageController;
use App\Http\Controllers\ApplicantProfileController;

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
    return Inertia::render('Public/Home');
});

Route::get('/recruiter/dashboard', ShowDesktopPageController::class)->middleware(['auth', 'verified'])->name('recruiter.dashboard');


Route::get('/jobs', [JobBoardController::class, 'index'])->name('public.jobs');

Route::middleware(['auth', 'role:applicant'])->group(function () {
    Route::get('/profile', [ApplicantProfileController::class, 'edit'])->name('applicant.profile.edit');
    Route::patch('/profile', [ApplicantProfileController::class, 'update'])->name('applicant.profile.update');
    Route::put('/profile/image', [ApplicantProfileController::class, 'updateImage'])->name('applicant.profile.updateImage');
    Route::delete('/profile', [ApplicantProfileController::class, 'destroy'])->name('applicant.profile.destroy');


    Route::prefix('/jobs/application')->group(function () {
        Route::get('/{job_listing}/create', [JobBoardController::class, 'apply'])->name('public.jobs.application.create');

        Route::post('/{job_listing}/store', [JobBoardController::class, 'storeApplication'])->name('public.jobs.application.store');
    });
});

Route::middleware(['auth', 'role:recruiter'])->prefix('/recruiter')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('recruiter.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('recruiter.profile.update');
    Route::put('/profile/image', [ProfileController::class, 'updateImage'])->name('recruiter.profile.updateImage');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('recruiter.profile.destroy');

    Route::get('/cv-extractor', [CVExtractorController::class, 'index'])->name('recruiter.extractor');

    Route::get('/cv-evaluation/show/{evaluation}', [EvaluationController::class, 'show'])->name('recruiter.evaluation.show');

    Route::get('/cv-evaluation/create', [EvaluationController::class, 'create'])->name('recruiter.evaluation.create');
    Route::post('/cv-evaluation/store', [EvaluationController::class, 'store'])->name('recruiter.evaluation.store');

    Route::get('/cv-export', [EvaluationController::class, 'export'])->name('recruiter.evaluation.export');
    Route::post('/cv-import', [EvaluationController::class, 'import'])->name('recruiter.evaluation.import');

    Route::get('/cv-pdf/{evaluation}', [EvaluationController::class, 'downloadPDF'])->name('recruiter.evaluation.downloadPDF');

    Route::get('/job/all', [JobListingController::class, 'index'])->name('recruiter.job.index');

    Route::get('/job/create', [JobListingController::class, 'create'])->name('recruiter.job.create');
    Route::post('/job/store', [JobListingController::class, 'store'])->name('recruiter.job.store');

    Route::get('/job/{job_listing}/edit', [JobListingController::class, 'edit'])->name('recruiter.job.edit');
    Route::patch('/job/{job_listing}/update', [JobListingController::class, 'update'])->name('recruiter.job.update');

    Route::get('/job/{job_listing}/applications', [JobApplicationController::class, 'index'])->name('recruiter.job.applications.index');
    Route::get('/job/application/{job_application}', [JobApplicationController::class, 'show'])->name('recruiter.job.applications.show');

    Route::patch('/job/application/{job_application}/updateStatus', [JobApplicationController::class, 'updateStatus'])->name('recruiter.job.applications.updateStatus');

    Route::get('/job/{job_listing}/applications-export', [JobApplicationController::class, 'export'])->name('recruiter.job.applications.export');

    Route::get('/users', [UserController::class, 'index'])->name('recruiter.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('recruiter.users.show');

    Route::delete('/users/{user}/delete', [UserController::class, 'destroy'])->name('recruiter.users.destroy');
});

Route::get('/role-login', [RoleController::class, 'index'])->name('role-login')->middleware(['auth']);

require __DIR__ . '/auth.php';
