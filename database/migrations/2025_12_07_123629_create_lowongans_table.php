<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            
            // Data Lowongan
            $table->string('kode_lowongan', 50)->unique();
            $table->string('judul_lowongan');
            
            // Foreign Keys untuk data dropdown (disarankan)
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->unsignedBigInteger('bidang_id')->nullable();
            $table->unsignedBigInteger('tipe_lowongan_id')->nullable();
            $table->unsignedBigInteger('penempatan_cabang_id')->nullable();

            $table->string('lokasi_kerja', 100);
            $table->string('pendidikan_terakhir', 50);

            // Deskripsi & Persyaratan
            $table->longText('jobdesk');
            $table->longText('kualifikasi');

            // Jadwal & Status
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->enum('status_lowongan', ['aktif', 'tidak aktif', 'dihapus', 'selesai'])->default('aktif');
            
            // Audit & Timestamps
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Definisi Foreign Key (Opsional, tapi disarankan)
            // (Anda harus sudah memiliki tabel master/users/kantor_cabang)
            // $table->foreign('kategori_id')->references('id')->on('kategori_lowongan')->onDelete('set null');
            // ... dan foreign keys lainnya
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};