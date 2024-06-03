<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'job_title',
        'job_details',
        'api_json',
        'status'
    ];

    protected $casts = [
        'job_details' => 'array',
        'api_json' => 'array'
    ];

    public function recruiter()
    {
        return $this->belongsTo(User::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function getShortDescriptionAttribute()
    {
        return  \Illuminate\Support\Str::words($this->job_details['company_profile'], 20);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query
                ->where('job_title', 'like', '%' . $search . '%');
        });
    }

    // Method to check if a JobListing has an application with a given user
    public function hasApplicationWithUser($userId)
    {
        return $this->jobApplications()->where('user_id', $userId)->exists();
    }
}
