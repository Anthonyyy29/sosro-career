<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    protected $table = 'lowongan';

    protected $fillable = [
        'kode_lowongan',
        'judul_lowongan',
        'kategori',
        'bidang',
        'tipe_lowongan',
        'penempatan_cabang',
        'lokasi_kerja',
        'tanggal_mulai',
        'tanggal_akhir',
        'jobdesk',
        'kualifikasi',
        'status_lowongan',
        'created_by',
    ];

}
