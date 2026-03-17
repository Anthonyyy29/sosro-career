<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $blueprint) {
            // Kolom untuk Gaji dan Fasilitas
            $blueprint->bigInteger('expected_salary')->nullable()->after('penyakit');
            $blueprint->text('expected_facilities')->nullable()->after('expected_salary');
            
            // Kolom Ketersediaan
            $blueprint->string('ready_dinas', 10)->default('Ya')->after('expected_facilities');
            $blueprint->string('ready_placed_out', 10)->default('Ya')->after('ready_dinas');
            
            // Kolom Referensi
            $blueprint->string('company_reference')->nullable()->after('ready_placed_out');
            
            // Kolom Minat (Simpan sebagai JSON)
            $blueprint->json('minat')->nullable()->after('company_reference');
        });
    }

    public function down(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'expected_salary', 
                'expected_facilities', 
                'ready_dinas', 
                'ready_placed_out', 
                'company_reference', 
                'minat'
            ]);
        });
    }
};