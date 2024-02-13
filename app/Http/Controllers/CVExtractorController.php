<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CVExtractorController extends Controller
{
    public function index()
    {
        return Inertia::render('Recruiter/Extractor/Index');
    }
}
