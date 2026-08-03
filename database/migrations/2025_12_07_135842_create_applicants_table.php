<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            // $table->unsignedBigInteger('job_id'); // lowongan yang dilamar
            $table->string('status')->default('Pending'); // Pending, Reviewed, Accepted, Rejected
            $table->timestamps();

            // Relasi ke tabel lowongan
            // $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            // $table->dropForeign('applicants_job_id_foreign');
            // $table->dropColumn('job_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicants');
    }

};
