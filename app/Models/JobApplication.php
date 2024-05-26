<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'data' => 'array',
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

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query
                ->where('user.first_name', 'like', '%' . $search . '%');
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('application_status', 'like', '%' . $status . '%');
        })->when($filters['sort'] ?? null, function ($query, $sort) {
            $query->orderBy('score', 'desc');
        });

        if (!isset($filters['sort'])) {
            $query->orderBy('created_at', 'asc');
        }
    }
}
