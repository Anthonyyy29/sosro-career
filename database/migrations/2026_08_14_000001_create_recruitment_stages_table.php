<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_stages', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // cocok dengan nilai applications.status, mis. 'administration', 'study case'
            $table->string('label'); // label sisi admin
            $table->string('applicant_label')->nullable(); // override label sisi pelamar, null = pakai `label`
            $table->string('color_classes'); // kelas Tailwind buat badge
            $table->boolean('is_universal')->default(false); // true buat pending/accepted/rejected
            $table->boolean('is_bulk_updatable')->default(false); // true kalau muncul di dropdown "Update Massal"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_stages');
    }
};
