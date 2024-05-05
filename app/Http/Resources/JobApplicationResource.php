<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->uuid,
            'user' => $this->whenLoaded('user'),
            'job_listing' => $this->whenLoaded('job_listing'),
            'data' => $this->data,
            'download_link' => $this->media ? $this->media->baseMedia->getUrl() : null,
            'application_status' => $this->application_status
        ];
    }
}
