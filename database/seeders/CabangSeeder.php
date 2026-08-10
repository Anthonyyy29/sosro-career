<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        $cabangs = [
            ['nama' => 'Kantor Pusat', 'kelompok' => 'HO'],

            ['nama' => 'KPW Bali Nusra', 'kelompok' => 'KPW'],
            ['nama' => 'KPW Jakarta Banten', 'kelompok' => 'KPW'],
            ['nama' => 'KPW Jawa Barat', 'kelompok' => 'KPW'],
            ['nama' => 'KPW Jawa Tengah', 'kelompok' => 'KPW'],
            ['nama' => 'KPW Jawa Timur', 'kelompok' => 'KPW'],
            ['nama' => 'KPW Kalimantan Sulawesi', 'kelompok' => 'KPW'],
            ['nama' => 'KPW Sumbagsel Babel', 'kelompok' => 'KPW'],
            ['nama' => 'KPW Sumut NAD - Sumbar Kepri', 'kelompok' => 'KPW'],

            ['nama' => 'Pabrik Cakung', 'kelompok' => 'Pabrik'],
            ['nama' => 'Pabrik Cibitung', 'kelompok' => 'Pabrik'],
            ['nama' => 'Pabrik Deli Serdang', 'kelompok' => 'Pabrik'],
            ['nama' => 'Pabrik Gianyar', 'kelompok' => 'Pabrik'],
            ['nama' => 'Pabrik Mojokerto', 'kelompok' => 'Pabrik'],
            ['nama' => 'Pabrik Palembang', 'kelompok' => 'Pabrik'],
            ['nama' => 'Pabrik Pandaan', 'kelompok' => 'Pabrik'],
            ['nama' => 'Pabrik Purbalingga', 'kelompok' => 'Pabrik'],
            ['nama' => 'Pabrik Sentul', 'kelompok' => 'Pabrik'],
            ['nama' => 'Pabrik Slawi', 'kelompok' => 'Pabrik'],
            ['nama' => 'Pabrik Ungaran', 'kelompok' => 'Pabrik'],

            ['nama' => 'Kebun', 'kelompok' => 'Lainnya'],
            ['nama' => 'Poci Kreasi Mandiri', 'kelompok' => 'Lainnya'],
        ];

        foreach ($cabangs as $cabang) {
            Cabang::updateOrCreate(['nama' => $cabang['nama']], $cabang);
        }
    }
}
