<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobListingResource;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobBoardController extends Controller
{
    public function index()
    {
        $job_listings = JobListingResource::collection(JobListing::all());
        return Inertia::render(
            'Public/Jobs',
            [

                'job_listings' => $job_listings
            ]
        );
    }
}
