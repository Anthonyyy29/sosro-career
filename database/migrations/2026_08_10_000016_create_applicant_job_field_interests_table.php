<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicant_job_field_interests', function (Blueprint $table) {
            $table->foreignId('applicant_profile_id')->constrained('applicant_profiles')->cascadeOnDelete();
            $table->foreignId('job_field_id')->constrained('job_fields')->cascadeOnDelete();
            $table->integer('rank'); // 1 = paling diminati

            $table->primary(['applicant_profile_id', 'job_field_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_job_field_interests');
    }
};
