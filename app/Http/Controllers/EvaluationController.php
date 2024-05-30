<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Exports\CVExport;
use App\Models\Evaluation;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\EvaluationResource;
use Illuminate\Http\Client\ConnectionException;

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
                try {
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

                $evaluation = Evaluation::create([
                    'user_id' => 2,
                    'data' => $response->json()
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
