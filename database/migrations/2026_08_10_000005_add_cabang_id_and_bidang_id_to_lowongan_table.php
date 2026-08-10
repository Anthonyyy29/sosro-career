<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->foreignId('cabang_id')->nullable()->after('penempatan_cabang')
                ->constrained('cabangs')->nullOnDelete();
            $table->foreignId('bidang_id')->nullable()->after('bidang')
                ->constrained('job_fields')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cabang_id');
            $table->dropConstrainedForeignId('bidang_id');
        });
    }
};
