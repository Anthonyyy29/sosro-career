<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'city', 'message', 'status', 'admin_id', 'cabang_id'];

    // Relasi: admin yang benar-benar membalas pesan ini (diisi pas markAsReplied)
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    // Relasi: cabang yang ditugaskan menangani pesan ini (semua admin di cabang ini bisa lihat & balas)
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}