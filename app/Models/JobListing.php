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
        'status'
    ];

    protected $casts = [
        'job_details' => 'array'
    ];

    public function recruiter()
    {
        $this->belongsTo(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query
                ->where('job_title', 'like', '%' . $search . '%');
        });
    }
}
