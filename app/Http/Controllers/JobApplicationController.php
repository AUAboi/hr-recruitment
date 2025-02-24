<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Exports\CVExport;
use App\Models\JobListing;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JobApplicationsExport;
use App\Http\Resources\JobListingResource;
use App\Http\Resources\JobApplicationResource;
use Illuminate\Http\Client\ConnectionException;

class JobApplicationController extends Controller
{
    public function index(Request $request, JobListing $job_listing)
    {
        $filters = $request->all('status', 'sort');

        $job_applications =  $job_listing->jobApplications()
            ->with(['user', 'media', 'user.media', 'user.roles'])
            ->filter($filters)
            ->paginate(30)
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

    public function downloadPDF(JobApplication $job_application)
    {
        $file = $job_application->media->baseMedia;

        if (!$file) {
            return response('Not Found', 404);
        }

        return $file;
    }

    public function revaluateScore(JobApplication $job_application)
    {


        try {
            $response = Http::withQueryParameters([
                'profile' => is_array($job_application->data) ? json_encode($job_application->data) : $job_application->data,
                'jd' => isset($job_application->jobListing->api_json) ? json_encode($job_application->jobListing->api_json) : json_encode($job_application->jobListing->job_details),

            ])->post('http://127.0.0.1:5431' . '/scoring');


            $job_application->score = $response->json();



            $job_application->relavancy_score = $response->json()['relavancy_score'];
            $job_application->skill_score = $response->json()['skill_score'];
            $job_application->experience_score = $response->json()['experience_score'];

            $job_application->save();
        } catch (ConnectionException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Score evaluated');
    }
}
