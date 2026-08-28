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
        foreach (DB::table('lowongan')->whereNotNull('bidang_id')->get() as $lowongan) {
            $jobField = DB::table('job_fields')->where('id', $lowongan->bidang_id)->first();
            if ($jobField) {
                DB::table('lowongan')->where('id', $lowongan->id)->update(['bidang' => $jobField->nama]);
            }
        }

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

        foreach (DB::table('lowongan')->whereNotNull('bidang')->get() as $lowongan) {
            $jobField = DB::table('job_fields')->where('nama', $lowongan->bidang)->first();
            if ($jobField) {
                DB::table('lowongan')->where('id', $lowongan->id)->update(['bidang_id' => $jobField->id]);
            }
        }

        Schema::table('lowongan', function (Blueprint $table) {
            $table->dropColumn('bidang');
        });
    }
};
