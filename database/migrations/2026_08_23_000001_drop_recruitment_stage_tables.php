<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Definisi tahapan seleksi sudah pindah ke config/recruitment.php, jadi kedua
 * tabel ini tidak pernah dibaca aplikasi lagi. Yang membacanya dulu cuma
 * App\Models\RecruitmentStage, dan itu sekarang membaca config.
 *
 * down() membuat ulang STRUKTUR kedua tabel, jadi migrasi ini bisa di-rollback.
 * Tapi ISINYA tidak ikut kembali -- dan memang tidak perlu, karena datanya
 * sekarang tinggal di config/recruitment.php.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('recruitment_stage_pipeline'); // anak dulu, baru induk
        Schema::dropIfExists('recruitment_stages');
    }

    public function down(): void
    {
        Schema::create('recruitment_stages', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('applicant_label')->nullable();
            $table->string('color_classes');
            $table->boolean('is_universal')->default(false);
            $table->boolean('is_bulk_updatable')->default(false);
            $table->timestamps();
        });

        Schema::create('recruitment_stage_pipeline', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->foreignId('recruitment_stage_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order');
            $table->timestamps();

            $table->unique(['kategori', 'recruitment_stage_id']);
            $table->unique(['kategori', 'order']);
        });
    }
};
