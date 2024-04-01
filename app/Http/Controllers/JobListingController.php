<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class JobListingController extends Controller
{
    public function create()
    {
        return Inertia::render('Recruiter/Jobs/Create');
    }
}
