<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->dropColumn(['bidang', 'penempatan_cabang']);
        });
    }

    public function down(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->string('bidang')->nullable()->after('kategori');
            $table->string('penempatan_cabang')->nullable()->after('tipe_lowongan');
        });
    }
};
