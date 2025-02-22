<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
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
        $request->validate(['job_prompt' => 'required|string']);

        $available_tags = ['AI', 'Web', 'ML', 'UI/UX', 'IOT', 'Game Development', '3D Model'];

        try {
            $inputs = $request->job_prompt;

            $query = "You are an expert job description maker. Create a job description based on user inputs.";
            $formatInstructions = json_encode([
                "job_title" => "String, the title of the job. Make title better.",
                "job_details" => [
                    "company_profile" => "String, a brief profile of the company",
                    "requirements" => "Array of strings, requirements for the job",
                    "what_will_you_do" => "Array of strings, tasks the person will perform",
                    "benefits" => "Array of strings, benefits the company provides",
                ],
                "tags" => "Array of tags that may belong to this job. Use only the available tags given below",
                "status" => "String, the job description status (e.g., 'DRAFT')"
            ], JSON_PRETTY_PRINT);

            $template = <<<EOT
Answer the user query in the following JSON format:
{format_instructions}

Available Tags:
{available_tags}

Query:
{query}

Inputs:
{Inputs}
EOT;


            $response =
                OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [[
                        'role' => 'user',
                        'content' =>
                        str_replace(
                            ['{format_instructions}', '{available_tags}', '{query}', '{Inputs}'],
                            [$formatInstructions, implode(',', $available_tags), $query, $inputs],
                            $template
                        ),
                    ]]
                ]);

            // Uncomment for debugging
            // dd($response);

            return response()->json(json_decode($response->choices[0]->message->content, true));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
