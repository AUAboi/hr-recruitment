<?php

use App\Http\Controllers\API\GenerateJobDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:recruiter'])->group(function () {
    Route::post('/generate_job_description', GenerateJobDescription::class);
});

Route::get('/{country}/cities', function ($country) {
    $response = Http::get("http://api.geonames.org/searchJSON", [
        'country' => $country,
        'featureClass' => 'P',
        'maxRows' => 10,
        'username' => 'tripeasy' // Replace with your GeoNames username
    ]);

    return response($response->body())->header('Content-Type', 'application/json');
});
