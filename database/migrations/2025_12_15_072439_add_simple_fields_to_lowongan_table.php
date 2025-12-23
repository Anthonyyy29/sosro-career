<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('judul_lowongan');
            $table->string('bidang')->nullable()->after('kategori');
            $table->string('tipe_lowongan')->nullable()->after('bidang');
            $table->string('penempatan_cabang')->nullable()->after('tipe_lowongan');

            // opsional: hapus kolom _id biar bersih
            $table->dropColumn([
                'kategori_id',
                'bidang_id',
                'tipe_lowongan_id',
                'penempatan_cabang_id',
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            //
        });
    }
};
