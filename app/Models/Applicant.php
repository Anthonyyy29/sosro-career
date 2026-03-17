<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ApplicantProfile;

class Applicant extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'profile_completed',
        'consent_accepted',
        'biodata_progress',
        'personal_completed',
        'family_completed',
        'education_completed',
        'experience_completed',
        'documents_completed',
        'biodata_submitted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->hasOne(ApplicantProfile::class);
    }

    public function documents()
    {
        return $this->hasMany(ApplicantDocument::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function calculateProgress()
    {
        $progress = 0;

        if ($this->personal_completed) $progress += 30;
        if ($this->family_completed) $progress += 15;
        if ($this->education_completed) $progress += 20;
        if ($this->experience_completed) $progress += 20;
        if ($this->documents_completed) $progress += 15;

        $this->biodata_progress = $progress;
        $this->save();
    }
}
