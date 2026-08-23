<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
 * applications.status selama ini varchar tanpa nilai bawaan. Nilai 'pending'
 * dipasang dari kode (ApplyController), jadi jalur pembuatan lain -- seeder,
 * impor, endpoint baru -- yang lupa mengisinya akan menghasilkan NULL. Lamaran
 * seperti itu tidak cocok dengan filter mana pun dan praktis tak terlihat.
 *
 * Pakai SQL mentah karena mengubah kolom lewat Schema::table() butuh paket
 * doctrine/dbal, yang tidak dipasang di proyek ini.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE applications MODIFY status VARCHAR(255) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE applications MODIFY status VARCHAR(255) NOT NULL');
    }
};
