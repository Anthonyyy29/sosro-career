<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            // Di letakkan setelah kolom penyakit agar rapi di database
            $table->enum('perokok', ['ya', 'tidak'])->nullable()->after('penyakit');
            $table->enum('bertato', ['ya', 'tidak'])->nullable()->after('perokok');
        });
    }

    public function down(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->dropColumn(['perokok', 'bertato']);
        });
    }
};
