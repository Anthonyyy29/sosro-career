<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\Lowongan;
use Carbon\Carbon;

class PageController extends Controller
{
    public function beranda() {
        return view('pages.beranda');
    }

    public function lowongan()
    {
        // AUTO TAKEDOWN (simple version) || Seharusnya pakai Cron yaitu khusus Laravel Scheduler + Command.
        Lowongan::where('status_lowongan', 'aktif')
            ->whereDate('tanggal_akhir', '<', now())
            ->update([
                'status_lowongan' => 'selesai'
            ]);

        // AMBIL LOWONGAN AKTIF SAJA
        $lowongan = Lowongan::where('status_lowongan', 'aktif')
            ->whereDate('tanggal_akhir', '>=', now())
            ->orderBy('tanggal_akhir', 'ASC')
            ->get();

        return view('pages.lowongan', compact('lowongan'));
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
