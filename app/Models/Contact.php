<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'city', 'message', 'status', 'admin_id'];

    // Relasi: Pesan ini dimiliki/ditugaskan ke seorang Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}