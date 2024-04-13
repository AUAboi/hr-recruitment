<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class JobBoardController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Jobs');
    }
}
