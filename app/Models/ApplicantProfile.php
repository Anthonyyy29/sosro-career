<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantProfile extends Model
{
    protected $fillable = [
        'applicant_id', 'nik', 'jk', 'tempat_lahir', 'tanggal_lahir',
        'tinggi_badan', 'berat_badan', 'alamat', 'domisili', 'phone', 'agama',
        'status_nikah', 'jenis_sim', 'instagram', 'linkedin', 'expected_salary', 'expected_facilities',
        'ready_dinas', 'ready_placed_out', 'company_reference',
        'ex_employee','ex_company_name','ex_last_position', 'penyakit', 'perokok', 'bertato',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'jenis_sim' => 'array',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(ApplicantFamilyMember::class, 'applicant_profile_id');
    }

    public function workExperiences()
    {
        return $this->hasMany(ApplicantWorkExperience::class, 'applicant_profile_id');
    }

    public function formalEducations()
    {
        return $this->hasMany(ApplicantFormalEducation::class, 'applicant_profile_id');
    }

    public function informalEducations()
    {
        return $this->hasMany(ApplicantInformalEducation::class, 'applicant_profile_id');
    }

    public function jobFieldInterests()
    {
        return $this->belongsToMany(JobField::class, 'applicant_job_field_interests', 'applicant_profile_id', 'job_field_id')
            ->withPivot('rank');
    }
}
