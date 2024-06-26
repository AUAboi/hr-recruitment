<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\JobListing;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\JobListingResource;
use Illuminate\Http\Client\ConnectionException;

class JobBoardController extends Controller
{
    public function index()
    {
        $job_listings = JobListingResource::collection(JobListing::all());
        return Inertia::render(
            'Public/Jobs',
            [

                'job_listings' => $job_listings,
            ]
        );
    }

    public function storeApplication(Request $request, JobListing $job_listing)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:10000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'required|mimes:pdf,webp,png,jpeg,jpg,doc,docx|max:10000'
        ]);


        try {
            $file = $request->file('pdf');

            $response = Http::asMultipart()->attach(
                'file',
                file_get_contents($file->path()),
                'cv.pdf'
            )
                ->withQueryParameters([
                    'query' => 'Extract the useful info from the CV'
                ])
                ->post(config('app.api_url') . '/extract-cv-info');
        } catch (ConnectionException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $job_application = $job_listing->jobApplications()->create([
            'user_id' => auth()->id(),
            'data' => $response->json(),
            'score' => null,
            'uuid' => Str::uuid()
        ]);


        $jobListingMedia = $job_application->media()->create([]);
        $jobListingMedia->baseMedia()->associate(
            $jobListingMedia->addMedia($file)->toMediaCollection('pdf')
        )->save();


        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $jobListingAtMedia = $job_application->attachmentsMedia()->create([]);
                $jobListingAtMedia->baseMedia()->associate(
                    $jobListingAtMedia->addMedia($attachment)->toMediaCollection('attachments')
                )->save();
            }
        }

        $responseScore = Http::withQueryParameters([
            'profile' => $response->json(),
            'jd' => isset($job_listing->api_json) ? json_encode($job_listing->api_json) : json_encode($job_listing->job_details),
        ])->post(config('app.api_url') . '/scoring');

        if (isset($responseScore->json()['relavancy_score'])) {
            $job_application->score = $responseScore->json();

            $job_application->relavancy_score = $responseScore->json()['relavancy_score'];
            $job_application->skill_score = $responseScore->json()['skill_score'];
            $job_application->experience_score = $responseScore->json()['exprience_score'];

            $job_application->save();
        }
        return redirect()->route('public.jobs')->with('success', 'Uploaded successfully');
    }

    public function apply(JobListing $job_listing)
    {
        return Inertia::render('Public/Job', [
            'job_listing' => new JobListingResource($job_listing),
            'has_applied' => $job_listing->hasApplicationWithUser(auth()->id()),
        ]);
    }
}
