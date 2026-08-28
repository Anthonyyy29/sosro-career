<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * applications.status selama ini varchar tanpa nilai bawaan. Nilai 'pending'
 * dipasang dari kode (ApplyController), jadi jalur pembuatan lain -- seeder,
 * impor, endpoint baru -- yang lupa mengisinya akan menghasilkan NULL. Lamaran
 * seperti itu tidak cocok dengan filter mana pun dan praktis tak terlihat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }
};
