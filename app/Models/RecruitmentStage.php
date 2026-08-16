<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RecruitmentStage extends Model
{
    protected $fillable = ['key', 'label', 'applicant_label', 'color_classes', 'is_universal', 'is_bulk_updatable'];

    protected $casts = [
        'is_universal' => 'boolean',
        'is_bulk_updatable' => 'boolean',
    ];

    public function pipelineEntries()
    {
        return $this->hasMany(RecruitmentStagePipeline::class);
    }

    // Semua tahap + pivot-nya, di-query sekali per request lalu dipakai ulang oleh semua accessor
    // di bawah (dropdown/badge manggil ini berkali-kali dalam 1 render halaman -- ini biar gak N+1).
    // Aman lintas-request: PHP shared-nothing per request (non-Octane), static property gak nyangkut.
    private static ?Collection $cache = null;

    private static function loaded(): Collection
    {
        return static::$cache ??= static::with('pipelineEntries')->get();
    }

    // Urutan tahap per kategori lowongan: ['Profesional' => ['administration', 'psikotes', ...], ...]
    public static function pipelines(): array
    {
        $pipelines = [];
        foreach (static::loaded() as $stage) {
            foreach ($stage->pipelineEntries as $entry) {
                $pipelines[$entry->kategori][$entry->order] = $stage->key;
            }
        }
        foreach ($pipelines as $kategori => $stagesByOrder) {
            ksort($stagesByOrder);
            $pipelines[$kategori] = array_values($stagesByOrder);
        }

        return $pipelines;
    }

    // Tahap yang selalu tersedia di semua kategori, di luar pipeline: pending/accepted/rejected.
    public static function universalStages(): array
    {
        return static::loaded()->where('is_universal', true)->pluck('key')->all();
    }

    public static function labels(): array
    {
        return static::loaded()->pluck('label', 'key')->all();
    }

    // Override label khusus sisi pelamar -- cuma tahap yang beneran punya override yang masuk sini.
    public static function applicantLabels(): array
    {
        return static::loaded()->whereNotNull('applicant_label')->pluck('applicant_label', 'key')->all();
    }

    public static function colors(): array
    {
        return static::loaded()->pluck('color_classes', 'key')->all();
    }

    public static function bulkUpdateStages(): array
    {
        return static::loaded()->where('is_bulk_updatable', true)->pluck('key')->all();
    }

    // Semua key tahap yang valid -- dipakai buat whitelist Rule::in() di validasi.
    public static function allKeys(): array
    {
        return static::loaded()->pluck('key')->all();
    }
}
