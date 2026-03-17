<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Admin\Lowongan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApplyController extends Controller
{
    public function store(Request $request, Lowongan $lowongan)
    {
        $user = Auth::user();

        // 🔒 Pastikan punya applicant
        if (!$user || !$user->applicant) {
            abort(403, 'Profil belum dibuat.');
        }

        $applicant = $user->applicant;

        // 🔒 HARD CHECK — wajib profile_completed
        if (!$applicant->profile_completed) {
            abort(403, 'Lengkapi profil sebelum melamar.');
        }

        // 🔒 LIMIT 3x APPLY DALAM 14 HARI
        $applyCount = Application::where('applicant_id', $applicant->id)
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->count(); // Hapus whereIn status agar semua status terhitung

        // dd($applyCount);
        if ($applyCount >= 3) {
            return back()->with('error', 'Batas maksimal 3 lamaran dalam 14 hari telah tercapai.');
        }

        // 🔒 Cegah apply ke lowongan yang sama
        $alreadyApplied = Application::where('applicant_id', $applicant->id)
            ->where('lowongan_id', $lowongan->id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'Kamu sudah melamar lowongan ini.');
        }

        // ✅ Simpan lamaran
        Application::create([
            'applicant_id' => $applicant->id,
            'lowongan_id'  => $lowongan->id,
            'status'       => 'pending',
        ]);

        return redirect()
            ->route('applicant.applications.index')
            ->with('success', 'Lamaran Anda untuk posisi ' . $lowongan->judul_lowongan . ' telah berhasil dikirim!');
    }
}