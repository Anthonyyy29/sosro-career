<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Lowongan as AdminLowongan;
use App\Models\User;
use App\Models\Applicant;
use App\Models\Lowongan;
use App\Models\Application;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | CARD SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalUsers      = User::count();
        $totalApplicants = Applicant::count();
        $lowonganActive  = AdminLowongan::where('status_lowongan', 'aktif')->count();
        $applicantsLolos = Applicant::where('status', 'accepted')->count();
        $applicantsGagal = Applicant::where('status', 'accepted')->count();
        $applicantsProses = Applicant::whereNotIn('status', ['accepted', 'rejected'])->count();
        $recentActivities = Application::with(['lowongan', 'applicant.user']);
        $allApplications = Application::all();

        $applications = $recentActivities->latest()->get();

        // Hitung Rekap
        $rekap = [
            'total' => $applications->count(),
            'diterima' => $applications->where('status', 'accepted')->count(),
            'ditolak' => $applications->where('status', 'rejected')->count(),
            'proses' => $applications->whereNotIn('status', ['accepted', 'rejected'])->count(),
        ];
        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK GRAFIK PIPELINE REKRUTMEN
        |--------------------------------------------------------------------------
        */

        $pipelineData = Application::join('lowongan', 'applications.lowongan_id', '=', 'lowongan.id')
            ->select('lowongan.kategori', 'applications.status', DB::raw('count(*) as total'))
            ->whereNotIn('applications.status', ['accepted', 'rejected'])
            ->groupBy('lowongan.kategori', 'applications.status')
            ->get()
            ->groupBy('kategori'); // Mengelompokkan hasil berdasarkan

        $lowonganByKategori = AdminLowongan::where('status_lowongan', 'aktif')
            ->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori'); // Hasilnya: ['Profesional' => 5, 'Magang' => 2, ...]

        /*
        |--------------------------------------------------------------------------
        | AKTIVITAS TERBARU
        |--------------------------------------------------------------------------
        */

        $recentActivities = Application::with(['lowongan', 'applicant.user'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalApplicants',
            'lowonganActive',
            'applicantsLolos',
            'applicantsGagal',
            'applicantsProses',
            'pipelineData',
            'rekap',
            'recentActivities',
            'lowonganByKategori'
        ));
    }
}