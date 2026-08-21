<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ApplicantProfile;

class Applicant extends Model
{
    /**
     * Dokumen yang diunggah setelah pelamar diterima (accepted).
     * Key = nilai kolom applicant_documents.type, dipakai bareng oleh DocumentController,
     * view upload, dan recalculateDocumentsCompleted() -- jangan ditulis ulang di tempat lain.
     *
     * Dikelompokkan sesuai tampilan halaman upload.
     */
    public const DOCUMENT_GROUPS = [
        'Identitas & Kependudukan' => [
            'foto'        => ['label' => 'Pas Foto Terbaru (3x4)', 'required' => true,  'limit' => 2],
            'ktp'         => ['label' => 'Scan KTP',               'required' => true,  'limit' => 2],
            'kk'          => ['label' => 'Scan Kartu Keluarga (KK)', 'required' => true, 'limit' => 2],
            'akta_lahir'  => ['label' => 'Scan Akta Kelahiran',    'required' => true,  'limit' => 2],
        ],
        'Pendidikan & Lamaran' => [
            'ijazah'         => ['label' => 'Scan Ijazah & Transkrip Nilai', 'required' => true, 'limit' => 2],
            'cv'             => ['label' => 'CV', 'required' => true, 'limit' => 5],
            'surat_lamaran'  => ['label' => 'Scan Surat Lamaran', 'required' => false, 'limit' => 2, 'note' => 'Jika CV belum lengkap'],
        ],
        'Keuangan & Perpajakan' => [
            'rekening_bca' => ['label' => 'Fotokopi Buku Rekening BCA (halaman pertama)', 'required' => true, 'limit' => 2],
            'npwp'         => ['label' => 'Scan NPWP', 'required' => false, 'limit' => 2],
        ],
        'Dokumen Pendukung' => [
            'surat_nikah'     => ['label' => 'Scan Surat Nikah', 'required' => false, 'limit' => 2, 'note' => 'Jika sudah menikah'],
            'surat_ket_kerja' => ['label' => 'Scan Surat Keterangan Kerja', 'required' => false, 'limit' => 2, 'note' => 'Jika punya pengalaman kerja'],
            'bpjs_kes'        => ['label' => 'Scan Kartu BPJS Kesehatan', 'required' => false, 'limit' => 2, 'note' => 'Jika sudah ada'],
            'bpjs_tk'         => ['label' => 'Scan Kartu BPJS Ketenagakerjaan', 'required' => false, 'limit' => 2, 'note' => 'Jika sudah ada'],
        ],
    ];

    /** Semua definisi dokumen tanpa pengelompokan: ['ktp' => [...], ...] */
    public static function documentDefinitions(): array
    {
        return array_merge(...array_values(self::DOCUMENT_GROUPS));
    }

    /** Type dokumen yang wajib diunggah */
    public static function requiredDocumentTypes(): array
    {
        return array_keys(array_filter(
            self::documentDefinitions(),
            fn ($def) => $def['required']
        ));
    }

    protected $fillable = [
        'user_id',
        'status',
        'profile_completed',
        'consent_accepted',
        'biodata_progress',
        'personal_completed',
        'family_completed',
        'education_completed',
        'experience_completed',
        'documents_completed',
        'biodata_submitted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->hasOne(ApplicantProfile::class);
    }

    public function documents()
    {
        return $this->hasMany(ApplicantDocument::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Dokumen baru wajib setelah pelamar diterima di salah satu lamarannya.
    // Dokumen nempel ke Applicant (bukan per Application), jadi cukup 1 lamaran accepted.
    public function hasAcceptedApplication(): bool
    {
        return $this->applications()->where('status', 'accepted')->exists();
    }

    public function needsDocumentSubmission(): bool
    {
        return $this->hasAcceptedApplication() && ! $this->documents_completed;
    }

    public function recalculateDocumentsCompleted(): void
    {
        $required = self::requiredDocumentTypes();
        $have = $this->documents()->whereIn('type', $required)->pluck('type')->unique();

        $this->documents_completed = $have->count() === count($required);
        $this->save();
    }

    // Progress biodata = 4 tab form (dokumen tidak dihitung di sini, sudah pindah
    // ke halaman terpisah yang baru wajib setelah lamaran accepted).
    public function calculateProgress()
    {
        $progress = 0;

        if ($this->personal_completed) $progress += 35;
        if ($this->family_completed) $progress += 15;
        if ($this->education_completed) $progress += 25;
        if ($this->experience_completed) $progress += 25;

        $this->biodata_progress = $progress;
        $this->save();
    }
}
