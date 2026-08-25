<?php

namespace App\Models;

use App\Models\Admin\Lowongan;
use Illuminate\Database\Eloquent\Model;

/*
 * Satu kelompok kandidat yang disodorkan ke user untuk dipilih salah satu.
 *
 * Dua keadaan tahap "Konfirmasi User" tinggal di sini, bukan di applications.status:
 *   unconfirmed -> status 'menunggu', belum ada yang dipilih
 *   confirmed   -> selected_application_id sudah terisi
 */
class UserConfirmation extends Model
{
    protected $fillable = [
        'lowongan_id', 'email_user', 'status', 'selected_application_id',
        'dipilih_oleh', 'dipilih_admin_id', 'confirmed_at', 'created_by', 'expires_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    public function items()
    {
        return $this->hasMany(UserConfirmationItem::class);
    }

    public function selectedApplication()
    {
        return $this->belongsTo(Application::class, 'selected_application_id');
    }

    // Admin yang membuat kelompok ini.
    public function pembuat()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    // Admin yang menandai pilihan, kalau bukan user yang memilih lewat tautan.
    public function adminPemilih()
    {
        return $this->belongsTo(Admin::class, 'dipilih_admin_id');
    }

    /*
     * Membuat satu kelompok konfirmasi berikut isinya, lalu memindahkan status
     * semua lamaran itu ke tahap "Konfirmasi User".
     *
     * SATU-SATUNYA tempat kelompok dibuat -- dipakai jalur centang (Update Massal)
     * maupun jalur langsung (modal satu pelamar), supaya keduanya tidak mungkin
     * menghasilkan data yang berbeda bentuk.
     *
     * $catatanPerLamaran: [application_id => catatan interview]
     */
    public static function buatUntuk(
        \Illuminate\Support\Collection $applications,
        string $emailUser,
        array $catatanPerLamaran,
        ?int $adminId,
        int $masaBerlakuHari,
    ): self {
        return \Illuminate\Support\Facades\DB::transaction(
            function () use ($applications, $emailUser, $catatanPerLamaran, $adminId, $masaBerlakuHari) {
                $konfirmasi = static::create([
                    'lowongan_id' => $applications->first()->lowongan_id,
                    'email_user' => $emailUser,
                    'status' => 'menunggu',
                    'created_by' => $adminId,
                    'expires_at' => now()->addDays($masaBerlakuHari),
                ]);

                foreach ($applications as $application) {
                    $konfirmasi->items()->create([
                        'application_id' => $application->id,
                        'catatan_interview' => $catatanPerLamaran[$application->id],
                    ]);

                    $application->update(['status' => 'konfirmasi user']);
                }

                return $konfirmasi;
            }
        );
    }

    public function sudahDipilih(): bool
    {
        return $this->selected_application_id !== null;
    }

    public function masihBerlaku(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isFuture();
    }

    /*
     * Menetapkan kandidat terpilih. SATU-SATUNYA tempat pilihan ditulis --
     * dipakai halaman publik (user) maupun tombol admin, supaya keduanya tidak
     * mungkin berperilaku beda.
     *
     * $sumber: 'user' kalau lewat tautan, 'admin' kalau ditandai manual.
     *
     * Sengaja TIDAK mengubah status lamaran mana pun: yang terpilih tetap di tahap
     * "Konfirmasi User" sampai admin memindahkannya ke Offering, dan yang tidak
     * terpilih dibiarkan apa adanya.
     */
    public function pilihKandidat(int $applicationId, string $sumber, ?int $adminId = null): void
    {
        $this->update([
            'selected_application_id' => $applicationId,
            'dipilih_oleh' => $sumber,
            'dipilih_admin_id' => $sumber === 'admin' ? $adminId : null,
            'confirmed_at' => now(),
            'status' => 'selesai',
        ]);
    }
}
