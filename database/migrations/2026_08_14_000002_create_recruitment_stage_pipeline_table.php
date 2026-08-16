<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_stage_pipeline', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); // 'Profesional' / 'Management Trainee' / 'Magang' -- varchar bebas, konsisten sama lowongan.kategori
            $table->foreignId('recruitment_stage_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order'); // urutan tahap dalam pipeline kategori ini
            $table->timestamps();

            $table->unique(['kategori', 'recruitment_stage_id']);
            $table->unique(['kategori', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_stage_pipeline');
    }
};
