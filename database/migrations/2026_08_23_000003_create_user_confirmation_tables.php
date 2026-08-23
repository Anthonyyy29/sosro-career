<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tahap "Konfirmasi User": beberapa kandidat dari SATU lowongan disodorkan ke user
 * (pihak yang meminta lowongan) lewat satu tautan, dan user memilih salah satu.
 *
 * Kolom `status` tabel `applications` cuma bisa menyimpan satu nilai per lamaran,
 * jadi tidak cukup untuk menyatakan "kelompok kandidat ini sedang menunggu keputusan".
 * Itu sebabnya butuh tabel sendiri: satu baris per kelompok, plus satu baris per
 * kandidat di dalamnya.
 *
 * Dua keadaan yang dimaksud (unconfirmed / confirmed) tinggal di sini, bukan di
 * applications.status:
 *   unconfirmed = status 'menunggu', selected_application_id masih kosong
 *   confirmed   = selected_application_id sudah terisi
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_confirmations', function (Blueprint $table) {
            $table->id();

            // Semua kandidat dalam satu kelompok wajib dari lowongan yang sama.
            $table->foreignId('lowongan_id')->constrained('lowongan')->cascadeOnDelete();

            // Alamat email user, diketik admin tiap kali. Tidak disimpan di tabel
            // lowongan karena bisa berbeda per kelompok.
            $table->string('email_user');

            $table->string('status')->default('menunggu'); // 'menunggu' | 'selesai'

            $table->foreignId('selected_application_id')->nullable()
                ->constrained('applications')->nullOnDelete();

            // Siapa yang menentukan pilihannya: user lewat tautan, atau admin manual.
            $table->string('dipilih_oleh')->nullable(); // 'user' | 'admin'
            $table->foreignId('dipilih_admin_id')->nullable()
                ->constrained('admins')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('expires_at');

            $table->timestamps();
        });

        Schema::create('user_confirmation_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_confirmation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();

            // Catatan hasil interview yang ditulis admin, ditampilkan ke user
            // sebagai bahan pertimbangan memilih.
            $table->text('catatan_interview');

            $table->timestamps();

            // Satu pelamar tidak boleh muncul dua kali dalam kelompok yang sama.
            // Namanya ditulis pendek manual: nama bawaan Laravel untuk dua kolom ini
            // panjangnya lewat batas 64 karakter MySQL.
            $table->unique(['user_confirmation_id', 'application_id'], 'uc_items_confirmation_application_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_confirmation_items');
        Schema::dropIfExists('user_confirmations');
    }
};
