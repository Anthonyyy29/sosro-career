<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantFormalEducation extends Model
{
    protected $table = 'applicant_formal_educations';

    protected $fillable = [
        'applicant_profile_id',
        'jenjang',
        'sekolah',
        'jurusan',
        'nilai',
        'tahun_masuk',
        'tahun_lulus',
        'is_current_edu',
    ];

    protected $casts = [
        'is_current_edu' => 'boolean',
    ];

    public function profile()
    {
        return $this->belongsTo(ApplicantProfile::class, 'applicant_profile_id');
    }
}
