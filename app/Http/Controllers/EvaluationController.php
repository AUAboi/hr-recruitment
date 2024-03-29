<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Evaluation;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EvaluationController extends Controller
{
    public function show(Evaluation $evaluation)
    {
        return Inertia::render('Recruiter/Extractor/Show', [
            'evaluation' => $evaluation->load('user')
        ]);
    }

    public function create()
    {
        $evaluations = Evaluation::all();
        return Inertia::render('Recruiter/Extractor/Create', ['evaluations' => $evaluations]);
    }

    public function store(Request $request)
    {
        try {
            $response = Http::asMultipart()->attach(
                'file',
                file_get_contents($request->file('file')),
                'cv.pdf'
            )
                ->withQueryParameters([
                    'query' => 'Extract the useful info from the CV'
                ])
                ->post('http://127.0.0.1:5431/extract-cv-info');
        } catch (ConnectionException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $evaluation = Evaluation::create([
            'user_id' => 2,
            'data' => $response->json()
        ]);

        $evaluationtMedia = $evaluation->media()->create([]);
        $evaluationtMedia->baseMedia()->associate(
            $evaluationtMedia->addMedia($request->file('file'))->toMediaCollection()
        )->save();

        return redirect()->back()->with('success', 'Uploaded successfully');
    }
}
