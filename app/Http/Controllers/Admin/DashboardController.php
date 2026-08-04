<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Lowongan as AdminLowongan;
use App\Models\User;
use App\Models\Applicant;
use App\Models\Lowongan;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $isSuperAdmin = Auth::user()->role === 'superadmin';
        $cabang = Auth::user()->cabang;

        // Scope query Application berdasarkan cabang lowongan-nya, kecuali superadmin
        $scopeApplicationByLowongan = fn ($query) => $query->when(!$isSuperAdmin, function ($q) use ($cabang) {
            $q->whereHas('lowongan', fn ($q2) => $q2->where('penempatan_cabang', $cabang));
        });

        /*
        |--------------------------------------------------------------------------
        | CARD SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::whereHas('applicant.applications.lowongan', function ($q) use ($isSuperAdmin, $cabang) {
            if (!$isSuperAdmin) {
                $q->where('penempatan_cabang', $cabang);
            }
        })->count();

        $totalApplicants = Applicant::whereHas('applications.lowongan', function ($q) use ($isSuperAdmin, $cabang) {
            if (!$isSuperAdmin) {
                $q->where('penempatan_cabang', $cabang);
            }
        })->count();

        $lowonganActive = AdminLowongan::where('status_lowongan', 'aktif')
            ->when(!$isSuperAdmin, fn ($q) => $q->where('penempatan_cabang', $cabang))
            ->count();

        $applicantsLolos = $scopeApplicationByLowongan(Application::where('status', 'accepted'))->count();
        $applicantsGagal = $scopeApplicationByLowongan(Application::where('status', 'rejected'))->count();
        $applicantsProses = $scopeApplicationByLowongan(Application::whereNotIn('status', ['accepted', 'rejected']))->count();

        $applications = $scopeApplicationByLowongan(Application::with(['lowongan', 'applicant.user']))
            ->latest()
            ->get();

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
            ->when(!$isSuperAdmin, fn ($q) => $q->where('lowongan.penempatan_cabang', $cabang))
            ->groupBy('lowongan.kategori', 'applications.status')
            ->get()
            ->groupBy('kategori'); // Mengelompokkan hasil berdasarkan

        $lowonganByKategori = AdminLowongan::where('status_lowongan', 'aktif')
            ->when(!$isSuperAdmin, fn ($q) => $q->where('penempatan_cabang', $cabang))
            ->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori'); // Hasilnya: ['Profesional' => 5, 'Magang' => 2, ...]

        /*
        |--------------------------------------------------------------------------
        | AKTIVITAS TERBARU
        |--------------------------------------------------------------------------
        */

        $recentActivities = $scopeApplicationByLowongan(Application::with(['lowongan', 'applicant.user']))
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