<?php

namespace App\Http\Controllers;

use App\Models\UserConfirmation;
use Illuminate\Http\Request;

/*
 * Halaman yang dibuka user (pihak peminta lowongan) lewat tautan di email.
 *
 * TANPA LOGIN. Yang menjaganya: tanda tangan Laravel pada URL (middleware 'signed',
 * dipasang di routes/web.php) plus batas waktu di kolom expires_at.
 *
 * Halaman ini sengaja dibuat sesempit mungkin: hanya bisa melihat kandidat dalam
 * kelompok ini beserta catatan yang ditulis admin, dan memilih satu. Tidak bisa
 * melihat lamaran lain, tidak bisa mengubah status, tidak bisa mengunduh berkas.
 */
class UserConfirmationPublicController extends Controller
{
    public function show(UserConfirmation $konfirmasi)
    {
        return $this->tampilkan($konfirmasi, kedaluwarsa: ! $konfirmasi->masihBerlaku() && ! $konfirmasi->sudahDipilih());
    }

    public function select(Request $request, UserConfirmation $konfirmasi)
    {
        $request->validate(['application_id' => 'required|integer']);

        // Sudah pernah dipilih -> pilihan pertama menang. Tautan bisa diklik dua kali
        // atau diteruskan ke orang lain; itu bukan error, cukup diabaikan.
        if (! $konfirmasi->sudahDipilih()) {
            if (! $konfirmasi->masihBerlaku()) {
                return $this->tampilkan($konfirmasi, kedaluwarsa: true);
            }

            $adaDalamKelompok = $konfirmasi->items()
                ->where('application_id', $request->application_id)
                ->exists();

            if (! $adaDalamKelompok) {
                return $this->tampilkan($konfirmasi, pesanGagal: 'Kandidat itu tidak ada dalam daftar ini.');
            }

            $konfirmasi->pilihKandidat($request->application_id, 'user');
        }

        // Halaman hasil ditampilkan langsung, bukan redirect. Tanda tangan URL berlaku
        // per-alamat, jadi tanda tangan milik POST ini tidak sah untuk alamat GET --
        // mengarahkan ulang ke sana justru akan ditolak 403.
        return $this->tampilkan($konfirmasi, baruDipilih: true);
    }

    private function tampilkan(
        UserConfirmation $konfirmasi,
        bool $baruDipilih = false,
        bool $kedaluwarsa = false,
        ?string $pesanGagal = null,
    ) {
        $konfirmasi->refresh()->load([
            'lowongan', 'items.application.applicant.user', 'selectedApplication.applicant.user',
        ]);

        return view('konfirmasi-user.show', compact('konfirmasi', 'baruDipilih', 'kedaluwarsa', 'pesanGagal'));
    }
}
