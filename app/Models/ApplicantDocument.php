<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantDocument extends Model
{
    protected $fillable = [
        'applicant_id',
        'type',
        'file_path',
        'is_required',
        'extracted_data',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'extracted_data' => 'array',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
