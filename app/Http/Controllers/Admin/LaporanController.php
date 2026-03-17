<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Admin\Lowongan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\LaporanPelamarExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $posisiId = $request->get('lowongan_id');
        $status = $request->get('status');    

        $query = Application::with(['lowongan', 'applicant.user']);

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

        $listStatus = Application::select('status')
        ->distinct()
        ->pluck('status');

        // Mengambil lowongan yang pernah dilamar saja
        $listLowongan = Lowongan::whereHas('applications')->get();

        $applications = $query->latest()->get();

        // Data untuk dropdown filter posisi
        $listLowongan = Lowongan::whereHas('applications')->get();
        
        // Hitung Rekap
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

        // Pastikan filter di Export sama persis dengan di Index
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