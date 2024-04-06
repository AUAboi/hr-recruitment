<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Redirect;

class JobListingController extends Controller
{
    public function create()
    {
        return Inertia::render('Recruiter/Jobs/Create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'job_details' => 'required',
            'job_details.job_title' => 'required|string',
            'job_details.company_profile' => 'required|string',
            'job_details.requirements' => 'required|array',
            'job_details.requirements.*' => 'distinct|string|required',
            'job_details.what_will_you_do' => 'required|array',
            'job_details.what_will_you_do.*' => 'distinct|string|required',
            'job_details.benefits' => 'required|array',
            'job_details.benefits.*' => 'distinct|string|required',
        ]);


        $job_listing = JobListing::create([
            'user_id' => $request->user()->id,
            'job_details' => $request->job_details
        ]);

        return redirect()->route('recruiter.job.edit', $job_listing->id)->with('success', 'Generated sucessfully!');
    }

    public function edit(JobListing $job_listing)
    {
        return Inertia::render('Recruiter/Jobs/Edit', [
            'id' => $job_listing->id,
            'job_details' => $job_listing->job_details,

        ]);
    }

    public function update(Request $request, JobListing $job_listing)
    {
        $request->validate([
            'job_details' => 'required',
            'job_details.job_title' => 'required|string',
            'job_details.company_profile' => 'required|string',
            'job_details.requirements' => 'required|array',
            'job_details.requirements.*' => 'distinct|string|required',
            'job_details.what_will_you_do' => 'required|array',
            'job_details.what_will_you_do.*' => 'distinct|string|required',
            'job_details.benefits' => 'required|array',
            'job_details.benefits.*' => 'distinct|string|required',
        ]);

        $job_listing->update([
            'job_details' => $request->job_details
        ]);

        return Redirect::back()->with('success', 'Saved sucessfully!');
    }
}
