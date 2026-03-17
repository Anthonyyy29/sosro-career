<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantDocument extends Model
{
    protected $fillable = [
        'applicant_id',
        'type',
        'file_path',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
