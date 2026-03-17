<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            // Tambahkan kolom personal baru
            $table->enum('jk', ['L', 'P'])->after('nama_lengkap')->nullable();
            $table->string('tinggi_badan')->after('tanggal_lahir')->nullable();
            $table->string('berat_badan')->after('tinggi_badan')->nullable();
            $table->text('domisili')->after('alamat')->nullable();
            $table->string('agama')->after('phone')->nullable();
            $table->string('status_nikah')->after('agama')->nullable();
            $table->json('jenis_sim')->after('status_nikah')->nullable();
            $table->string('instagram')->after('jenis_sim')->nullable();
            $table->string('linkedin')->after('instagram')->nullable();

            // Tambahkan kolom data dinamis (JSON)
            $table->json('data_keluarga')->after('linkedin')->nullable();
            $table->json('pendidikan_formal')->after('data_keluarga')->nullable();
            $table->json('pendidikan_informal')->after('pendidikan_formal')->nullable();
            $table->json('pengalaman_kerja')->after('pendidikan_informal')->nullable();

            // Info tambahan & Dokumen
            $table->string('ex_employee')->nullable();
            $table->text('penyakit')->nullable();
            $table->string('doc_foto')->nullable();
            $table->string('doc_cv')->nullable();
            $table->string('doc_ktp')->nullable();
            $table->string('doc_ijazah')->nullable();
            $table->string('doc_sim')->nullable();
            $table->string('doc_npwp')->nullable();
            $table->string('doc_bpjs_kes')->nullable();
            $table->string('doc_bpjs_tk')->nullable();
            $table->string('doc_lain')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            // List kolom yang dihapus jika migrasi di-rollback
            $table->dropColumn([
                'jk', 'tinggi_badan', 'berat_badan', 'domisili', 'agama', 
                'status_nikah', 'jenis_sim', 'instagram', 'linkedin', 
                'data_keluarga', 'pendidikan_formal', 'pendidikan_informal', 
                'pengalaman_kerja', 'ex_employee', 'penyakit', 
                'doc_foto', 'doc_cv', 'doc_ktp', 'doc_ijazah', 'doc_sim', 'doc_npwp', 'doc_bpjs_kes', 'doc_bpjs_tk', 'doc_lain'
            ]);
        });
    }
};
