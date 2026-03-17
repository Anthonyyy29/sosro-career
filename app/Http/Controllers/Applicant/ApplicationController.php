<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 🔒 Kalau belum punya data applicant
        if (!$user->applicant) {
            return redirect()
                ->route('applicant.profile.create')
                ->with('warning', 'Lengkapi profil terlebih dahulu sebelum melihat lamaran.');
        }

        $applicant = $user->applicant;

        // 🔒 Kalau profil belum lengkap
        if (!$applicant->profile_completed) {
            return redirect()
                ->route('applicant.profile.create')
                ->with('warning', 'Profil Anda belum lengkap.');
        }

        $applications = Application::with('lowongan')
            ->where('applicant_id', $applicant->id)
            ->latest()
            ->get();

        return view('applicant.applications.index', compact('applications'));
    }

}
