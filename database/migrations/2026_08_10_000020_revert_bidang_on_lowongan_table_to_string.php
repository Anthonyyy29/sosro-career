<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->string('bidang')->nullable()->after('kategori');
        });

        // backfill dari job_fields sebelum kolom bidang_id dihapus
        DB::table('lowongan')
            ->join('job_fields', 'lowongan.bidang_id', '=', 'job_fields.id')
            ->update(['lowongan.bidang' => DB::raw('job_fields.nama')]);

        Schema::table('lowongan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bidang_id');
        });
    }

    public function down(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->foreignId('bidang_id')->nullable()->after('bidang')
                ->constrained('job_fields')->nullOnDelete();
        });

        DB::table('lowongan')
            ->join('job_fields', 'lowongan.bidang', '=', 'job_fields.nama')
            ->update(['lowongan.bidang_id' => DB::raw('job_fields.id')]);

        Schema::table('lowongan', function (Blueprint $table) {
            $table->dropColumn('bidang');
        });
    }
};
