<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('city'); 
            $table->text('message');
            $table->enum('status', ['pending', 'forwarded', 'replied'])->default('pending');
            
            // Relasi ke tabel admins (menggunakan admin_id)
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
