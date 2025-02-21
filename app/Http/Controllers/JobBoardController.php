<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\JobListing;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\JobListingResource;
use Illuminate\Http\Client\ConnectionException;
use OpenAI;
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
            // E:/project/hr-recruitment
            $text = (new Pdf('D:/web_projects/hr-recruitment/pdftotext.exe'))->setPdf($request->file('pdf')->path())->text();
            $text = utf8_encode($text);

            // Ensure it's a plain string
            $text = (string) $text;
            // dd($text);

            // foreach ($request->file('pdf') as $file) {
            //     // Extract text from PDF
            //     $text = Pdf::getText($file->path());



            // Define the prompt for structured extraction
            $prompt = "Extract the following structured data from the CV: Name, Father Name, Phone Number, Address, Skills, Projects, Programming Languages, Education History, and Summary. Return the response strictly as a well-formed JSON object with keys in lowercase. Ensure the response is valid UTF-8 and does not contain any extra formatting.";


            // dd($prompt);
            // Send extracted text to ChatGPT API
            $apiKey = env('OPENAI_API_KEY');

            // dd(['role' => 'user', 'content' => $text]);

            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json'
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $prompt],
                    ['role' => 'user', 'content' => $text]
                ],
                'temperature' => 0.7
            ]);

            dd($response->json('choices.0.message.content'));

            $parsedData = $response->json('choices.0.message.content');
            // $parsedData = json_decode($response['choices'][0]['message']['content'], true);

            // dd($parsedData);
            // }
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
