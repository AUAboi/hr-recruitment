<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_title' => $this->job_title,
            'tags' => $this->tags,
            'job_details' => $this->job_details,
            'short_description' => $this->short_description,
            'type' => $this->type,
            'country' => $this->country,
            'created_at' => $this->created_at,
            'created_human' => $this->created_at->diffForHumans(),
            'city' => $this->city
        ];
    }
}
