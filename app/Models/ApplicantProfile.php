<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantProfile extends Model
{
    protected $fillable = [
        'applicant_id', 'nik', 'nama_lengkap', 'jk', 'tempat_lahir', 'tanggal_lahir',
        'tinggi_badan', 'berat_badan', 'alamat', 'domisili', 'phone', 'agama',
        'status_nikah', 'jenis_sim', 'instagram', 'linkedin', 'expected_salary', 'expected_facilities',
        'ready_dinas', 'ready_placed_out', 'company_reference', 'minat', 'data_keluarga',
        'pendidikan_formal', 'pendidikan_informal', 'pengalaman_kerja',
        'ex_employee','ex_company_name','ex_last_position', 'penyakit', 'perokok', 'bertato',
        'doc_foto', 'doc_cv', 'doc_ktp', 'doc_ijazah', 'doc_sim', 'doc_npwp', 'doc_bpjs_kes', 'doc_bpjs_tk', 'doc_lain'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'jenis_sim' => 'array',
        'minat' => 'array',
        'data_keluarga' => 'array',
        'pendidikan_formal' => 'array',
        'pendidikan_informal' => 'array',
        'pengalaman_kerja' => 'array',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
