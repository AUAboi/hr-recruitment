<?php

namespace App\Http\Resources;

use App\Models\JobApplication;
use App\Models\JobApplicationMedia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
            'user' => new UserResource($this->whenLoaded('user')),
            'job_listing' => $this->whenLoaded('job_listing'),
            'score' => is_array($this->score) ? $this->score : json_decode($this->score),
            'average_score' => is_array($this->score) ? (($this->relavancy_score + $this->skill_score + $this->experience_score) / 3) * 10 : null,
            'data' => is_array($this->data) ? $this->data : json_decode($this->data),
            'created_at' => $this->created_at->diffForHumans(),
            'media' => $this->media ? $this->media->baseMedia->getUrl() : null,
            'attachments' =>  $this->attachmentsMedia ? $this->attachmentsMedia->map(function ($attachment) {
                return
                    $attachment->baseMedia->getUrl();
            }) : null,
            'download_link' => $this->media ? $this->media->baseMedia->getUrl() : null,
            'application_status' => $this->application_status
        ];
    }
}
