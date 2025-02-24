<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobApplicationAttachmentsMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobApplication extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'job_listing_id',
        'uuid',
        'score',
        'application_status',
        'data',
        'relavancy_score',
        'experience_score',
        'skill_score',
    ];

    protected $casts = [
        'data' => 'array',
        'score' => 'array'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class);
    }

    public function media()
    {
        return $this->hasOne(JobApplicationMedia::class);
    }

    public function attachmentsMedia()
    {
        return $this->hasMany(JobApplicationAttachmentsMedia::class);
    }

    public function getAverageScoreAttribute()
    {
        $scores = $this->score;

        if (is_array($scores)) {
            $total = ($scores['relavancy_score'] ?? 0) + ($scores['skill_score'] ?? 0) + ($scores['experience_score'] ?? 0);
            return $total / 3;
        }

        return null; // Default value if scores are not an array
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('user.first_name', 'like', '%' . $search . '%');
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('application_status', 'like', '%' . $status . '%');
        })->when($filters['sort'] ?? null, function ($query, $sort) {
            switch ($sort) {
                case 'average':
                    $query->orderByRaw('(
                        (relavancy_score + skill_score + experience_score) / 3
                    ) DESC');
                    break;
                case 'relavancy':
                    $query->orderBy('relavancy_score', 'desc');
                    break;
                case 'skill':
                    $query->orderBy('skill_score', 'desc');
                    break;
                case 'experience':
                    $query->orderBy('experience_score', 'desc');
                    break;
            }
        });

        if (!isset($filters['sort'])) {
            $query->orderBy('created_at', 'asc');
        }
    }
}
