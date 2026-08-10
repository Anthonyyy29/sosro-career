<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantWorkExperience extends Model
{
    protected $fillable = [
        'applicant_profile_id',
        'perusahaan',
        'jabatan',
        'divisi',
        'gaji',
        'fasilitas',
        'masih_bekerja',
        'tanggal_masuk',
        'tanggal_keluar',
        'alasan',
        'kontak_referensi',
    ];

    protected $casts = [
        'masih_bekerja' => 'boolean',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
    ];

    public function profile()
    {
        return $this->belongsTo(ApplicantProfile::class, 'applicant_profile_id');
    }
}
