<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // relasi ke applicants
            $table->foreignId('applicant_id')
                ->constrained('applicants')
                ->cascadeOnDelete();

            // relasi ke tabel lowongan (nama tabel custom)
            $table->foreignId('lowongan_id')
                ->constrained('lowongan')
                ->cascadeOnDelete();

            $table->enum('status', ['submitted', 'review', 'accepted', 'rejected'])
                ->default('submitted');

            $table->timestamps();

            // supaya tidak bisa apply 2x ke lowongan yang sama
            $table->unique(['applicant_id', 'lowongan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
