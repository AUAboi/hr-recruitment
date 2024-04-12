<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobListingRequest;
use App\Models\JobListing;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Redirect;

class JobListingController extends Controller
{

    public function index(Request $request)
    {
        $filters = $request->all('search');
        $job_listings =  JobListing::orderBy('updated_at')
            ->where('user_id', $request->user()->id)
            ->filter($filters)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Recruiter/Jobs/Index', [
            'job_listings' => $job_listings,
            'filters' => $filters
        ]);
    }

    public function create()
    {
        return Inertia::render('Recruiter/Jobs/Create');
    }

    public function store(JobListingRequest $request)
    {
        $job_listing = JobListing::create([
            'user_id' => $request->user()->id,
            'job_details' => $request->job_details,
            'job_title' => $request->job_title
        ]);

        return redirect()->route('recruiter.job.edit', $job_listing->id)->with('success', 'Generated sucessfully!');
    }

    public function edit(JobListing $job_listing)
    {
        return Inertia::render('Recruiter/Jobs/Edit', [
            'id' => $job_listing->id,
            'job_details' => $job_listing->job_details,
            'job_title' => $job_listing->job_title

        ]);
    }

    public function update(JobListingRequest $request, JobListing $job_listing)
    {
        $job_listing->update([
            'job_details' => $request->job_details,
            'job_title' => $request->job_title
        ]);

        return Redirect::back()->with('success', 'Saved sucessfully!');
    }
}
