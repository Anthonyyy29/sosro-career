<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantInformalEducation extends Model
{
    protected $table = 'applicant_informal_educations';

    protected $fillable = [
        'applicant_profile_id',
        'kursus',
        'penyelenggara',
        'tahun',
    ];

    public function profile()
    {
        return $this->belongsTo(ApplicantProfile::class, 'applicant_profile_id');
    }
}
