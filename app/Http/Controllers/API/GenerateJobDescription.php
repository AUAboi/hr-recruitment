<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class GenerateJobDescription extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $response = Http::withQueryParameters([
                'inputs' => $request->job_prompt
            ])->post(config('app.api_url') . '/generate_job_description');
        } catch (ConnectionException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json($response->json());
    }
}
