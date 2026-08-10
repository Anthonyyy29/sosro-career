<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantFamilyMember extends Model
{
    protected $fillable = [
        'applicant_profile_id',
        'tipe',
        'nama',
        'hubungan',
        'pendidikan',
        'tempat_lahir',
        'tgl_lahir',
        'pekerjaan',
        'hp',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function profile()
    {
        return $this->belongsTo(ApplicantProfile::class, 'applicant_profile_id');
    }
}
