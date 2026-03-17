<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->integer('biodata_progress')->default(0)->after('profile_completed');

            $table->boolean('personal_completed')->default(false);
            $table->boolean('family_completed')->default(false);
            $table->boolean('education_completed')->default(false);
            $table->boolean('experience_completed')->default(false);
            $table->boolean('documents_completed')->default(false);

            $table->boolean('biodata_submitted')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'biodata_progress',
                'personal_completed',
                'family_completed',
                'education_completed',
                'experience_completed',
                'documents_completed',
                'biodata_submitted',
            ]);
        });
    }
};

