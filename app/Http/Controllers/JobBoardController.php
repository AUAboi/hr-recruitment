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
        $filters = $request->all('search', 'tags');

        $job_listings =  JobListing::orderBy('updated_at')->where('status', 'PUBLISHED')
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




        $text = (new Pdf('E:/project/hr-recruitment/pdftotext.exe'))->setPdf($request->file('pdf')->path())->text();

        $file = $request->file('pdf');


        // Define the prompt for structured extraction
        $prompt = "
        Extract the following structured data from the CV and return it as a valid JSON object with the specified fields:
        
        ### Fields:
        - **name** (str): Name of the applicant.
        - **last_name** (str): Father's name of the applicant.
        - **phone_no** (str): Phone number of the applicant.
        - **address** (str): Address of the applicant.
        - **skills** (List[str]): List of skills of the applicant.
        - **project** (List[str]): List of projects made by the applicant.
        - **programming_language** (List[str]): List of programming languages known by the applicant.
        - **education_history** (List[str]): Complete educational background of the applicant.
        - **summary** (str): A brief summary of the applicant.
        
        ### CV Text:
        ```$text```
        
        Return the extracted data as a **valid JSON object**.
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

        $job_application = $job_listing->jobApplications()->create([
            'user_id' => auth()->id(),
            'data' => $parsedData,
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

        $parsedDataString = json_encode($parsedData);

        $promptScore = "
You are an expert CV scorer acting as an HR manager. Your task is to evaluate a candidate's CV based on a given job description and provide scores for relevancy, skills, and experience.

### Scoring Criteria (1-100):
- **Relavancy Score:** How relevant the CV is to the job description.
- **Skill Score:** How well the candidate's skills match the job requirements.
- **Exprience Score:** How well the candidate's experience aligns with the job requirements.

### Instructions:
- Analyze skills, programming languages, and relevant experience from the job description and the user's CV.
- If the CV is **highly relevant**, assign high scores.
- If the CV **partially matches**, assign moderate scores.
- If the CV is **not relevant**, assign very low scores (e.g., below 20).
- Stricly return only a **valid JSON object** with `relavancy_score`, `skill_score`, and `experience_score` and no extra comments.
- The json attributes should be strictly as provided: `relavancy_score`, `skill_score`, and `experience_score`, do not change even a single letter in the attribute names.
`.

**Profile:**  
$parsedDataString

**Job Description:**  
" . (isset($job_listing->api_json) ? json_encode($job_listing->api_json, JSON_PRETTY_PRINT) : json_encode($job_listing->job_details, JSON_PRETTY_PRINT));

        $responseScore = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [[
                'role' => 'user',
                'content' => $promptScore
            ]]
        ]);
        $parsedScoreData = json_decode($responseScore['choices'][0]['message']['content'], true);

        if (isset($parsedScoreData['relavancy_score'])) {
            $job_application->score = $parsedScoreData;

            $job_application->relavancy_score = $parsedScoreData['relavancy_score'];
            $job_application->skill_score = $parsedScoreData['skill_score'];
            $job_application->experience_score = $parsedScoreData['experience_score'];

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
