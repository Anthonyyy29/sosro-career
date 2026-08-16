<?php

namespace Database\Seeders;

use App\Models\RecruitmentStage;
use App\Models\RecruitmentStagePipeline;
use Illuminate\Database\Seeder;

class RecruitmentStageSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            // key => [label, applicant_label, color_classes, is_universal, is_bulk_updatable]
            'pending' => ['Pending', 'Terkirim', 'bg-yellow-50 text-yellow-600 border-yellow-100', true, false],
            'administration' => ['Lolos Administrasi', null, 'bg-purple-50 text-purple-600 border-purple-100', false, true],
            'psikotes' => ['Psikotes', null, 'bg-blue-50 text-blue-600 border-blue-100', false, true],
            'interview' => ['Interview', null, 'bg-cyan-50 text-cyan-600 border-cyan-100', false, true],
            'study case' => ['Study Case', null, 'bg-indigo-50 text-indigo-600 border-indigo-100', false, false],
            'panel bod' => ['Panel BoD', null, 'bg-violet-50 text-violet-600 border-violet-100', false, false],
            'simulasi' => ['Simulasi', 'Simulasi Field', 'bg-orange-50 text-orange-600 border-orange-100', false, false],
            'offering' => ['Offering Letter', null, 'bg-pink-50 text-pink-600 border-pink-100', false, false],
            'mcu' => ['MCU', 'Medical Check Up', 'bg-teal-50 text-teal-600 border-teal-100', false, false],
            'accepted' => ['Accepted', 'Diterima (Hired)', 'bg-green-50 text-green-600 border-green-100', true, false],
            'rejected' => ['Rejected', 'Ditolak', 'bg-red-50 text-red-600 border-red-100', true, true],
        ];

        $stageIds = [];
        foreach ($stages as $key => [$label, $applicantLabel, $colorClasses, $isUniversal, $isBulkUpdatable]) {
            $stage = RecruitmentStage::updateOrCreate(
                ['key' => $key],
                [
                    'label' => $label,
                    'applicant_label' => $applicantLabel,
                    'color_classes' => $colorClasses,
                    'is_universal' => $isUniversal,
                    'is_bulk_updatable' => $isBulkUpdatable,
                ]
            );
            $stageIds[$key] = $stage->id;
        }

        $pipelines = [
            'Profesional' => ['administration', 'psikotes', 'interview', 'offering', 'mcu'],
            'Management Trainee' => ['administration', 'psikotes', 'interview', 'study case', 'panel bod', 'offering', 'mcu'],
            'Magang' => ['administration', 'psikotes', 'interview', 'simulasi', 'offering'],
        ];

        foreach ($pipelines as $kategori => $stageKeys) {
            foreach ($stageKeys as $index => $key) {
                RecruitmentStagePipeline::updateOrCreate(
                    ['kategori' => $kategori, 'recruitment_stage_id' => $stageIds[$key]],
                    ['order' => $index + 1]
                );
            }
        }
    }
}
