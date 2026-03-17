<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicant_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type'); // ktp, kk, ijazah, cv, dll
            $table->string('file_path');
            $table->boolean('is_required')->default(false);
            $table->json('extracted_data')->nullable(); // OCR future

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_documents');
    }
};
