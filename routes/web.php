<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
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

Route::middleware('auth')->group(function () {
    Route::get('/recruiter/profile', [ProfileController::class, 'edit'])->name('recruiter.profile.edit');
    Route::patch('/recruiter/profile', [ProfileController::class, 'update'])->name('recruiter.profile.update');
    Route::put('/recruiter/profile/image', [ProfileController::class, 'updateImage'])->name('recruiter.profile.updateImage');
    Route::delete('/recruiter/profile', [ProfileController::class, 'destroy'])->name('recruiter.profile.destroy');
});

Route::get('/role-login', [RoleController::class, 'index'])->name('role-login')->middleware(['auth']);

require __DIR__ . '/auth.php';
