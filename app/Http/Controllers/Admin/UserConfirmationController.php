<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesToCabang;
use App\Http\Controllers\Controller;
use App\Mail\KonfirmasiUserEmail;
use App\Models\Application;
use App\Models\UserConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/*
 * Sisi admin untuk tahap "Konfirmasi User".
 *
 * Admin mencentang beberapa pelamar dari satu lowongan, menulis catatan interview
 * per orang, dan memasukkan alamat email user. Sistem membuat satu kelompok
 * konfirmasi dan mengirim SATU tautan ke user untuk memilih salah satu kandidat.
 *
 * Halaman yang dibuka user ada di App\Http\Controllers\UserConfirmationPublicController.
 */
class UserConfirmationController extends Controller
{
    use ScopesToCabang;

    // Berapa lama tautan ke user berlaku.
    public const MASA_BERLAKU_HARI = 7;

    public function store(Request $request)
    {
        $request->validate([
            'email_user' => 'required|email',
            'catatan' => 'required|array|min:1',
            'catatan.*' => 'required|string|max:2000',
        ], [
            'email_user.required' => 'Alamat email user wajib diisi.',
            'email_user.email' => 'Alamat email user tidak valid.',
            'catatan.*.required' => 'Catatan interview wajib diisi untuk setiap kandidat.',
        ]);

        $catatanPerLamaran = $request->input('catatan');

        $applications = $this->lingkupCabang(
            Application::with(['applicant.user', 'lowongan'])->whereIn('id', array_keys($catatanPerLamaran))
        )->get();

        if ($applications->isEmpty()) {
            return back()->withErrors(['catatan' => 'Tidak ada kandidat yang bisa diproses.'])->withInput();
        }

        // Semua kandidat harus dari lowongan yang sama. Kalau dibiarkan campur, user
        // akan disodori kandidat dari posisi berbeda dan diminta memilih satu --
        // pertanyaan yang tidak masuk akal.
        $lowonganIds = $applications->pluck('lowongan_id')->unique();

        if ($lowonganIds->count() > 1) {
            return back()->withErrors([
                'catatan' => 'Semua kandidat harus berasal dari lowongan yang sama. '
                    .'Yang dipilih berasal dari '.$lowonganIds->count().' lowongan berbeda.',
            ])->withInput();
        }

        $konfirmasi = DB::transaction(function () use ($applications, $lowonganIds, $catatanPerLamaran, $request) {
            $konfirmasi = UserConfirmation::create([
                'lowongan_id' => $lowonganIds->first(),
                'email_user' => $request->email_user,
                'status' => 'menunggu',
                'created_by' => Auth::id(),
                'expires_at' => now()->addDays(self::MASA_BERLAKU_HARI),
            ]);

            foreach ($applications as $application) {
                $konfirmasi->items()->create([
                    'application_id' => $application->id,
                    'catatan_interview' => $catatanPerLamaran[$application->id],
                ]);

                $application->update(['status' => 'konfirmasi user']);
            }

            return $konfirmasi;
        });

        try {
            Mail::to($konfirmasi->email_user)->send(new KonfirmasiUserEmail($konfirmasi));
        } catch (\Exception $e) {
            logger()->error('Gagal kirim email konfirmasi user: '.$e->getMessage());

            return redirect()->route('admin.applicants')->with(
                'success',
                $applications->count().' kandidat dikirim ke tahap Konfirmasi User, '
                .'namun email ke '.$konfirmasi->email_user.' GAGAL dikirim.'
            );
        }

        return redirect()->route('admin.applicants')->with(
            'success',
            $applications->count().' kandidat dikirim ke '.$konfirmasi->email_user.' untuk dipilih.'
        );
    }

    // Admin menandai sendiri siapa yang terpilih, tanpa menunggu user membuka tautan.
    // Memakai method yang sama dengan halaman publik supaya dua jalur ini tidak bisa
    // berbeda perilakunya.
    public function pilihManual(Request $request, UserConfirmation $konfirmasi)
    {
        $request->validate(['application_id' => 'required|integer']);

        $terpilih = $konfirmasi->items()->where('application_id', $request->application_id)->exists();

        if (! $terpilih) {
            return back()->withErrors(['application_id' => 'Kandidat itu tidak ada dalam kelompok konfirmasi ini.']);
        }

        $adaSebelumnya = $konfirmasi->sudahDipilih();

        $konfirmasi->pilihKandidat($request->application_id, 'admin', Auth::id());

        return back()->with('success', $adaSebelumnya
            ? 'Pilihan diganti oleh admin.'
            : 'Kandidat ditandai terpilih oleh admin.');
    }
}
