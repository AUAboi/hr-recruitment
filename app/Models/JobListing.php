<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
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
}
