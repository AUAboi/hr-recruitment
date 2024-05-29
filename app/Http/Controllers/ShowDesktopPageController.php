<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class ShowDesktopPageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $recent_listing = auth()->user()->jobListings()->latest()->first();
        if ($recent_listing) {
            $recent_applications = $recent_listing->jobApplications()->get();
        } else {
            $recent_applications = [];
        }

        return Inertia::render('Recruiter/Dashboard', [
            'recent_applications_count' => count($recent_applications),
            'recent_listing' => $recent_listing,
        ]);
    }
}
