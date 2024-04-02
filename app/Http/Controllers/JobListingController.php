<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class JobListingController extends Controller
{
    public function create()
    {
        return Inertia::render('Recruiter/Jobs/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            "job_prompt" => "required|string"
        ]);

        try {
            $response = Http::withQueryParameters([
                'inputs' => $request->job_prompt
            ])->post(config('app.api_url') . '/generate_job_description');
        } catch (ConnectionException $e) {
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }

        $job_listing = JobListing::create([
            'user_id' => $request->user()->id,
            'job_details' => $response->json()
        ]);

        return redirect()->route('recruiter.job.edit', $job_listing->id)->with('sucess', 'Generated sucessfully!');
    }

    public function edit(JobListing $job_listing)
    {
        return Inertia::render('Recruiter/Jobs/Edit', [
            'job_listing' => $job_listing
        ]);
    }
}
