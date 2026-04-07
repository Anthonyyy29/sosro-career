<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LowonganController extends Controller
{
    private function generateKodeLowongan()
    {
        $last = Lowongan::orderBy('id', 'DESC')->first();

        if (!$last) {
            return 'LWG-0001';
        }

        $lastNumber = (int) substr($last->kode_lowongan, 4);
        $newNumber  = $lastNumber + 1;

        return 'LWG-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public function toggleStatus(Lowongan $lowongan)
    {
        if (Auth::user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang dapat mengubah status lowongan.');
        }

        if ($lowongan->status_lowongan === 'aktif') {
            $lowongan->status_lowongan = 'tidak aktif';
        } elseif ($lowongan->status_lowongan === 'tidak aktif') {
            $lowongan->status_lowongan = 'aktif';
        }

        $lowongan->save();

        return back()->with('success', 'Status lowongan berhasil diperbarui');
    }

    public function index(Request $request)
    {
        $query = Lowongan::withCount('applications')->with('admin');

        // Filter milik sendiri jika bukan superadmin
        if (Auth::user()->role !== 'superadmin') {
            $query->where('created_by', Auth::id());
        }
        
        // Filter Berdasarkan Judul
        if ($request->filled('search')) {
            $query->where('judul_lowongan', 'like', '%' . $request->search . '%');
        }

        // Filter Berdasarkan Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter Berdasarkan Lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi_kerja', 'like', '%' . $request->lokasi . '%');
        }

        // Sort Berdasarkan Tanggal
        $sort = $request->get('sort_date', 'latest');
        if ($sort == 'latest') {
            $query->latest();
        } else {
            $query->oldest();
        }

        $lowongan = $query->get();

        return view('admin.lowongan.index', compact('lowongan'));
    }

    public function create()
    {
        return view('admin.lowongan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'tanggal_akhir' => 'required|date|after_or_equal:today',
        ]);

        Lowongan::create([
            'kode_lowongan' => $this->generateKodeLowongan(),
            'judul_lowongan' => $request->judul_lowongan,
            'kategori' => $request->kategori,
            'bidang' => $request->bidang,
            'tipe_lowongan' => $request->tipe_lowongan,
            'penempatan_cabang' => $request->penempatan_cabang,
            'lokasi_kerja' => $request->lokasi_kerja,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_akhir' => $request->tanggal_akhir,
            'jobdesk' => $request->jobdesk ?? '',
            'kualifikasi' => $request->kualifikasi ?? '',
            'status_lowongan' => 'tidak aktif',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.lowongan.index')
            ->with('success', 'Lowongan berhasil ditambahkan');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'tanggal_akhir' => 'required|date|after_or_equal:today',
        ]);

        $lowongan = Lowongan::findOrFail($id);

        $lowongan->update([
            'judul_lowongan' => $request->judul_lowongan,
            'kategori' => $request->kategori,
            'bidang' => $request->bidang,
            'tipe_lowongan' => $request->tipe_lowongan,
            'penempatan_cabang' => $request->penempatan_cabang,
            'lokasi_kerja' => $request->lokasi_kerja,
            'tanggal_akhir' => $request->tanggal_akhir,
            'jobdesk' => $request->jobdesk ?? '',
            'kualifikasi' => $request->kualifikasi ?? '',
        ]);

        return redirect()->route('admin.lowongan.index')
            ->with('success', 'Lowongan berhasil diperbarui');
    }


    public function destroy(Lowongan $lowongan)
    {
        $lowongan->delete();

        return back()->with('success', 'Lowongan '. $lowongan->judul_lowongan .' berhasil dihapus');
    }
}
