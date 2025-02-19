<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\JobListing;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\JobListingResource;
use Illuminate\Http\Client\ConnectionException;
use OpenAI\Laravel\Facades\OpenAI;
use Spatie\PdfToText\Pdf;

class JobBoardController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->all('search');

        $job_listings =  JobListing::orderBy('updated_at')
            ->filter($filters)
            ->paginate(40)
            ->withQueryString();


        return Inertia::render(
            'Public/Jobs',
            [
                'job_listings' => $job_listings,
                'filters' => $filters
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



        if ($request->hasFile('pdf')) {

            $text = (new Pdf('E:/project/hr-recruitment/pdftotext.exe'))->setPdf($request->file('pdf')->path())->text();
            dd($text);
            foreach ($request->file('pdf') as $file) {
                // Extract text from PDF
                $text = Pdf::getText($file->path());
                dd($text);

                // Define the prompt for structured extraction
                $prompt = "Extract the following structured data from the CV:
            Name, Father Name, Phone Number, Address, Skills, Projects, Programming Languages, 
            Education History, and Summary. Return the response as a valid JSON object.
            
            CV Text:
            ```$text```
            ";

                // Send extracted text to ChatGPT API
                $client = OpenAI::client(env('OPENAI_API_KEY'));
                $response = $client->chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [[
                        'role' => 'user',
                        'content' => $prompt
                    ]]
                ]);


                $parsedData = json_decode($response['choices'][0]['message']['content'], true);

                dd($parsedData);
            }
        }



        // $job_application = $job_listing->jobApplications()->create([
        //     'user_id' => auth()->id(),
        //     'data' => $response->json(),
        //     'score' => null,
        //     'uuid' => Str::uuid()
        // ]);


        // $jobListingMedia = $job_application->media()->create([]);
        // $jobListingMedia->baseMedia()->associate(
        //     $jobListingMedia->addMedia($file)->toMediaCollection('pdf')
        // )->save();


        // if ($request->hasFile('attachments')) {
        //     foreach ($request->file('attachments') as $attachment) {
        //         $jobListingAtMedia = $job_application->attachmentsMedia()->create([]);
        //         $jobListingAtMedia->baseMedia()->associate(
        //             $jobListingAtMedia->addMedia($attachment)->toMediaCollection('attachments')
        //         )->save();
        //     }
        // }

        // $responseScore = Http::withQueryParameters([
        //     'profile' => $response->json(),
        //     'jd' => isset($job_listing->api_json) ? json_encode($job_listing->api_json) : json_encode($job_listing->job_details),
        // ])->post(config('app.api_url') . '/scoring');

        // if (isset($responseScore->json()['relavancy_score'])) {
        //     $job_application->score = $responseScore->json();

        //     $job_application->relavancy_score = $responseScore->json()['relavancy_score'];
        //     $job_application->skill_score = $responseScore->json()['skill_score'];
        //     $job_application->experience_score = $responseScore->json()['exprience_score'];

        //     $job_application->save();
        // }
        // return redirect()->route('public.jobs')->with('success', 'Uploaded successfully');
    }

    public function apply(JobListing $job_listing)
    {
        return Inertia::render('Public/Job', [
            'job_listing' => new JobListingResource($job_listing),
            'has_applied' => $job_listing->hasApplicationWithUser(auth()->id()),
        ]);
    }
}
