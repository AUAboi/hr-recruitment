<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Exports\CVExport;
use App\Exports\JobApplicationsExport;
use App\Models\JobListing;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\JobListingResource;
use App\Http\Resources\JobApplicationResource;

class JobApplicationController extends Controller
{
    public function index(Request $request, JobListing $job_listing)
    {
        $filters = $request->all('search');

        $job_applications =  $job_listing->jobApplications()
            ->with(['user', 'media', 'user.media'])
            ->orderBy('updated_at', 'DESC')
            ->filter($filters)
            ->paginate(40)
            ->withQueryString();


        return Inertia::render('Recruiter/Applications/Index', [
            'job_listing' => new JobListingResource($job_listing),
            'job_applications' => JobApplicationResource::collection($job_applications),
            'filters' => $filters
        ]);
    }

    public function updateStatus(JobApplication $job_application, Request $request)
    {
        $request->validate([
            'status' => 'required|in:PENDING,REJECTED,APPROVED',
        ]);


        $job_application->update([
            'application_status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Updated status succcesfully');
    }

    public function show(JobApplication $job_application)
    {
        return Inertia::render('Recruiter/Applications/Show', [
            'job_application' => new JobApplicationResource($job_application->load('user', 'media', 'user.media'))
        ]);
    }

    public function export(JobListing $job_listing)
    {
        return Excel::download(new JobApplicationsExport($job_listing), 'cv.xlsx');
    }
}
