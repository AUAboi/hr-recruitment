<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'job_details' => 'required',
            'job_title' => 'required|string',
            'job_details.company_profile' => 'required|string',
            'job_details.requirements' => 'required|array',
            'job_details.requirements.*' => 'distinct|string|required',
            'job_details.what_will_you_do' => 'required|array',
            'job_details.what_will_you_do.*' => 'distinct|string|required',
            'job_details.benefits' => 'required|array',
            'job_details.benefits.*' => 'distinct|string|required',
        ];
    }
}
