<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CVExtractorController extends Controller
{
    public function index()
    {
        $user = User::latest()->first();
        return Inertia::render('Recruiter/Extractor/Index', [
            'user' => new UserResource($user)
        ]);
    }
}
