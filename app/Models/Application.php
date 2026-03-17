<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'lowongan_id',
        'status',
        'notes',
    ];

    public function user()
    {
        // Karena Application tidak punya user_id secara langsung, jadi ambil User melalui Applicant
        return $this->hasOneThrough(
            User::class, 
            Applicant::class, 
            'id',           // Local key di Applicant
            'id',           // Local key di User
            'applicant_id', // Foreign key di Application
            'user_id'       // Foreign key di Applicant
        );
    }
    
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(\App\Models\Admin\Lowongan::class, 'lowongan_id');
    }
}
