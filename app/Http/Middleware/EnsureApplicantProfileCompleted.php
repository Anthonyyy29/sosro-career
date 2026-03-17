<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureApplicantProfileCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // jika belum punya applicant → suruh isi profil
        if (!$user || !$user->applicant) {
            return redirect()
                ->route('applicant.profile.create')
                ->with('warning', 'Silakan lengkapi biodata terlebih dahulu.');
        }

        $applicant = $user->applicant;

        // cek apakah profil sudah complete
        if (!$applicant->profile_completed) {
            return redirect()
                ->route('applicant.profile.create')
                ->with('warning', 'Lengkapi biodata sebelum melamar pekerjaan.');
        }

        return $next($request);
    }
}
