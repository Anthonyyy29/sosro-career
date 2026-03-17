<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            // Di letakkan setelah kolom ex_employee agar rapi di database
            $table->string('ex_company_name')->nullable()->after('ex_employee');
            $table->string('ex_last_position')->nullable()->after('ex_company_name');
        });
    }

    public function down(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->dropColumn(['ex_company_name', 'ex_last_position']);
        });
    }
};
