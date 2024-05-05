<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class JobApplicationMedia extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'media_id',
        'job_application_id',
    ];

    public function baseMedia()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
