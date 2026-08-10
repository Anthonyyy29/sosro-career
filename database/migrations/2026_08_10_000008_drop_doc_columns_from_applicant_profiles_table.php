<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const DOC_COLUMNS = [
        'doc_foto', 'doc_cv', 'doc_ktp', 'doc_ijazah', 'doc_sim',
        'doc_npwp', 'doc_bpjs_kes', 'doc_bpjs_tk', 'doc_lain',
    ];

    public function up(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->dropColumn(self::DOC_COLUMNS);
        });
    }

    public function down(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            foreach (self::DOC_COLUMNS as $column) {
                $table->string($column)->nullable();
            }
        });
    }
};
