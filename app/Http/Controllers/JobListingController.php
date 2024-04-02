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

        dd($response->json());
        // $job_listing = JobListing::create([
        //     'user_id' => $request->user()->id,
        //     'job_details' => $response->json()
        // ]);

        return redirect()->back()->with('sucess', 'Uploaded successfully');
    }
}
