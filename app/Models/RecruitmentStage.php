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

    // Daftar tahap disusun sekali saja tiap kali halaman dibuka, lalu dipakai ulang
    // oleh semua method di bawah -- soalnya dropdown dan badge memanggilnya
    // berkali-kali dalam satu halaman yang sama.
    //
    // Isinya tidak akan nyangkut ke pengunjung berikutnya: PHP memulai semuanya
    // dari nol tiap ada permintaan halaman baru.
    private static ?Collection $cache = null;

    // Sumber datanya sekarang config/recruitment.php, bukan lagi tabel database.
    //
    // Nama di sebelah kiri (key, label, color_classes, dst) sengaja dibuat sama
    // persis dengan nama kolom tabel yang lama, supaya semua method di bawah
    // tidak perlu ikut diubah sama sekali.
    private static function loaded(): Collection
    {
        return static::$cache ??= collect(config('recruitment.stages', []))
            ->map(fn (array $definisi, string $key) => [
                'key'               => $key,
                'label'             => $definisi['label'],
                'applicant_label'   => $definisi['applicant_label'] ?? null,
                'color_classes'     => $definisi['color'],
                'is_universal'      => $definisi['universal'] ?? false,
                'is_bulk_updatable' => $definisi['bulk'] ?? false,
            ])
            ->values();
    }

    // Urutan tahap per kategori lowongan: ['Profesional' => ['administration', 'psikotes', ...], ...]
    //
    // Dulu ini menyusun ulang urutannya dari kolom `order` di tabel pivot. Sekarang
    // urutannya sudah berupa posisi array di config, jadi tinggal diteruskan apa adanya.
    public static function pipelines(): array
    {
        return config('recruitment.pipelines', []);
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
