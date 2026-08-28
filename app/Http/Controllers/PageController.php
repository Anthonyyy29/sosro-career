<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\Lowongan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PageController extends Controller
{
    public function beranda() {
        return view('guest.home');
    }

    public function lowongan()
    {
        // 1. AUTO TAKEDOWN
        Lowongan::where('status_lowongan', 'aktif')
            ->whereDate('tanggal_akhir', '<', now())
            ->update(['status_lowongan' => 'selesai']);

        // 2. AMBIL LOWONGAN AKTIF
        $lowongan = Lowongan::with(['cabang'])
            ->where('status_lowongan', 'aktif')
            ->whereDate('tanggal_akhir', '>=', now())
            ->orderBy('tanggal_akhir', 'ASC')
            ->get();

        // 3. AMBIL APPLICANT (Cara paling aman)
        $applicant = null;
        
        if (Auth::check()) {
            $user = Auth::user();
            // Pastikan relasi 'applicant' sudah didefinisikan di Model User
            $applicant = $user->applicant; 
        }

        return view('guest.job', compact('lowongan', 'applicant'));
    }

    public function program() {
        return view('pages.program');
    }

    public function tentang() {
        return view('pages.tentang');
    }
 
    public function kontak() {
        return view('pages.kontak');
    }
}
