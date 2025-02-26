<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Exports\CVExport;
use App\Models\Evaluation;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\EvaluationResource;
use Illuminate\Http\Client\ConnectionException;
use Spatie\PdfToText\Pdf;

class EvaluationController extends Controller
{
    public function show(Evaluation $evaluation)
    {
        return Inertia::render('Recruiter/Extractor/Show', [
            'evaluation' => new EvaluationResource($evaluation->load('user', 'media', 'user.media'))
        ]);
    }

    public function create()
    {
        $evaluations = Evaluation::all();
        return Inertia::render('Recruiter/Extractor/Create', ['evaluations' => $evaluations]);
    }

    public function store(Request $request)
    {


        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|mimes:pdf|max:10000'
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Extract text from PDF
                $text = Pdf::getText($file->path());

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


                // Save structured data to database
                $evaluation = Evaluation::create([
                    'user_id' => 2,
                    'data' => $parsedData
                ]);

                $evaluationtMedia = $evaluation->media()->create([]);
                $evaluationtMedia->baseMedia()->associate(
                    $evaluationtMedia->addMedia($file)->toMediaCollection()
                )->save();
            }
        }

        return redirect()->back()->with('success', 'Uploaded successfully');
    }

    public function export()
    {
        return Excel::download(new CVExport, 'cv.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:1',
            'files.*' => 'required|mimes:csv,xlsl|max:10000'
        ]);

        Excel::import(new UsersImport, $request->file('files')[0]);

        return redirect()->back()->with('success', 'Imported!');
    }

    public function downloadPDF(Evaluation $evaluation)
    {
        $file = $evaluation->media->baseMedia;

        if (!$file) {
            return response('Not Found', 404);
        }

        return $file;
    }
}
