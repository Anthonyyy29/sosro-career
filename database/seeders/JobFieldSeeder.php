<?php

namespace Database\Seeders;

use App\Models\JobField;
use Illuminate\Database\Seeder;

class JobFieldSeeder extends Seeder
{
    public function run(): void
    {
        $jobFields = [
            'Administrasi',
            'Finance & Accounting',
            'General Affairs',
            'Human Resources & People Development',
            'Information Technology',
            'Internal Audit',
            'Marketing',
            'Produksi / Teknik',
            'Purchasing',
            'Quality Control',
            'Research & Development',
            'Sales & Distribution',
            'Supply Chain & Logistic',
        ];

        foreach ($jobFields as $nama) {
            JobField::updateOrCreate(['nama' => $nama]);
        }
    }
}
