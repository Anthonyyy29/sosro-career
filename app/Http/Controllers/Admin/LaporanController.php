<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Admin\Lowongan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\LaporanPelamarExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $posisiId = $request->get('lowongan_id');
        $status = $request->get('status');    

        $query = Application::with(['lowongan', 'applicant.user']);

        // SCOPE DATA LAPORAN (PENTING)
        // Jika bukan superadmin, hanya tampilkan laporan dari lowongan miliknya sendiri
        if (Auth::user()->role !== 'superadmin') {
            $query->whereHas('lowongan', function($q) {
                $q->where('penempatan_cabang', Auth::user()->cabang);
            });
        }

        // Filter Tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        // Filter Posisi
        if ($posisiId) {
            $query->where('lowongan_id', $posisiId);
        }

        // Filter Status
        if ($status) {
            $query->where('status', $status);
        }

        // Mengambil daftar status unik untuk dropdown (sesuai scope)
        $statusQuery = Application::query();
        if (Auth::user()->role !== 'superadmin') {
            $statusQuery->whereHas('lowongan', function($q) {
                $q->where('penempatan_cabang', Auth::user()->cabang);
            });
        }
        $listStatus = $statusQuery->select('status')->distinct()->pluck('status');

        // Mengambil daftar lowongan untuk dropdown filter (sesuai scope)
        $lowonganQuery = Lowongan::whereHas('applications');
        if (Auth::user()->role !== 'superadmin') {
            $lowonganQuery->where('penempatan_cabang', Auth::user()->cabang);
        }
        $listLowongan = $lowonganQuery->get();

        $applications = $query->latest()->get();
        
        // Hitung Rekap berdasarkan data yang sudah terfilter scope
        $rekap = [
            'total' => $applications->count(),
            'diterima' => $applications->where('status', 'accepted')->count(),
            'ditolak' => $applications->where('status', 'rejected')->count(),
            'proses' => $applications->whereNotIn('status', ['accepted', 'rejected'])->count(),
        ];

        return view('admin.laporan.index', compact('applications', 'rekap', 'startDate', 'endDate', 'listLowongan', 'listStatus'));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $posisiId = $request->get('lowongan_id');
        $status = $request->get('status');

        $query = Application::with(['lowongan', 'applicant.user']);

        // --- SCOPE DATA EXPORT (PENTING AGAR TIDAK BISA DITEMBAK) ---
        if (Auth::user()->role !== 'superadmin') {
            $query->whereHas('lowongan', function($q) {
                $q->where('penempatan_cabang', Auth::user()->cabang);
            });
        }

        // Filter Tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        if ($posisiId) {
            $query->where('lowongan_id', $posisiId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $applications = $query->latest()->get();

        return Excel::download(new LaporanPelamarExport($applications), 'Laporan_Pelamar_' . now()->format('Y-m-d') . '.xlsx');
    }
}