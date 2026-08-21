<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Alur lama ProfileController::store() meng-hardcode documents_completed = true
     * tiap kali biodata disubmit, tanpa cek file benar-benar diunggah. Akibatnya flag ini
     * false-positive buat pelamar yang tidak pernah upload dokumen -- dan itu bikin banner
     * "Lengkapi Dokumen" (yang cek ! documents_completed) tidak pernah muncul.
     *
     * Sekarang flag-nya dihitung dari isi applicant_documents, jadi data lama perlu diluruskan.
     */
    public function up(): void
    {
        // Sengaja hardcode (bukan baca konstanta model): daftar wajib bisa berubah nanti,
        // dan migration harus tetap merepresentasikan kondisi saat dia dijalankan.
        $required = ['foto', 'cv', 'ktp', 'kk', 'ijazah', 'akta_lahir', 'rekening_bca'];

        $completeIds = DB::table('applicant_documents')
            ->whereIn('type', $required)
            ->select('applicant_id')
            ->groupBy('applicant_id')
            ->havingRaw('COUNT(DISTINCT type) = ?', [count($required)])
            ->pluck('applicant_id');

        DB::table('applicants')->update(['documents_completed' => false]);

        if ($completeIds->isNotEmpty()) {
            DB::table('applicants')
                ->whereIn('id', $completeIds)
                ->update(['documents_completed' => true]);
        }
    }

    public function down(): void
    {
        // Tidak dibalik: nilai lama memang tidak akurat, mengembalikannya cuma memunculkan bug yang sama.
    }
};
