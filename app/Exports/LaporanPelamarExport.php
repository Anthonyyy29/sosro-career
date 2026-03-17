<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanPelamarExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $applications;

    public function __construct($applications)
    {
        $this->applications = $applications;
    }

    public function collection()
    {
        return $this->applications;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Lamar',
            'Nama Lengkap',
            'Email',
            'Posisi Lowongan',
            'Kategori',
            'Status Akhir',
        ];
    }

    public function map($application): array
    {
        static $rowNumber = 0;
        return [
            ++$rowNumber,
            $application->created_at->format('d/m/Y H:i'),
            $application->applicant->user->name ?? '-',
            $application->applicant->user->email ?? '-',
            $application->lowongan->judul_lowongan ?? '-',
            $application->lowongan->kategori ?? '-',
            strtoupper($application->status),
        ];
    }
}