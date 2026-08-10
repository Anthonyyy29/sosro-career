<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\Admin;
use App\Models\Cabang;
use App\Models\JobField;

class Lowongan extends Model
{
    protected $table = 'lowongan';

    protected $fillable = [
        'kode_lowongan',
        'judul_lowongan',
        'kategori',
        'bidang_id',
        'tipe_lowongan',
        'cabang_id',
        'lokasi_kerja',
        'tanggal_mulai',
        'tanggal_akhir',
        'jobdesk',
        'kualifikasi',
        'status_lowongan',
        'created_by',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class, 'lowongan_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function bidang()
    {
        return $this->belongsTo(JobField::class, 'bidang_id');
    }
}
