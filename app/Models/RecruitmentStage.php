<?php

namespace App\Models;

use Illuminate\Support\Collection;

/*
 * Pembaca definisi tahapan seleksi dari config/recruitment.php.
 *
 * Dulu ini model Eloquent yang membaca tabel recruitment_stages dan
 * recruitment_stage_pipeline. Kedua tabel itu sudah dihapus, dan kelasnya
 * sekarang cuma kumpulan method statis -- tidak menyentuh database sama sekali.
 *
 * Namanya sengaja tidak diubah supaya 15 titik pemanggilan di 4 berkas tidak
 * perlu ikut disunting.
 */
class RecruitmentStage
{
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

    // Tahap universal yang boleh DITUJU admin lewat dropdown, yaitu accepted/rejected.
    //
    // Bedanya dengan universalStages() di atas: yang ini membuang tahap awal
    // ('pending'), karena tahap awal cuma titik berangkat -- admin tidak boleh
    // memundurkan pelamar kembali ke sana.
    //
    // Dipakai buat merakit dropdown di admin/applicants/index.blade.php, supaya
    // accepted/rejected tidak perlu ditulis tangan lagi di sana.
    public static function universalDestinations(): array
    {
        return array_values(array_diff(
            static::universalStages(),
            [config('recruitment.initial')]
        ));
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

    // Aturan validasi untuk SEMUA tahap sekaligus, siap ditempel ke
    // $request->validate(). Daftar kolom wajibnya diambil dari kunci 'fields'
    // di config/recruitment.php, jadi tidak perlu ditulis satu per satu lagi
    // di controller seperti dulu.
    public static function fieldRules(): array
    {
        $rules = [];
        foreach (config('recruitment.stages', []) as $key => $definisi) {
            foreach ($definisi['fields'] ?? [] as $kolom) {
                $rules[$kolom] = 'required_if:next_status,'.$key;
            }
        }

        return $rules;
    }

    // Kelas email yang harus dikirim untuk tahap ini, atau null kalau tahap ini
    // memang sengaja tidak mengirim email apa pun.
    //
    // Sebagian tahap punya lebih dari satu kemungkinan email (psikotes dan
    // interview), dipilih berdasarkan isian admin -- itu yang diurus 'switch'.
    //
    // $input biasanya isi $request->all().
    public static function mailClass(string $key, array $input = []): ?string
    {
        $mail = config("recruitment.stages.{$key}.mail");

        if (empty($mail)) {
            return null;
        }

        if (is_string($mail)) {
            return $mail;
        }

        $penentu = $input[$mail['switch']] ?? null;

        if (is_string($penentu) && isset($mail['map'][$penentu])) {
            return $mail['map'][$penentu];
        }

        return $mail['default'];
    }

    /*
     * Urutan tahap per kategori KHUSUS untuk dropdown "Pindah ke Tahap" di modal
     * satu pelamar: sama dengan pipelines(), tapi tahap ber-'bulk_only' dibuang.
     *
     * Dibedakan dari pipelines() karena keduanya memang beda keperluan:
     * dropdown FILTER tetap harus bisa menyaring berdasarkan tahap bulk_only
     * (admin perlu melihat siapa saja yang sedang di tahap itu), sedangkan
     * dropdown PINDAH tidak boleh menawarkannya.
     */
    public static function selectablePipelines(): array
    {
        $hasil = [];

        foreach (static::pipelines() as $kategori => $tahap) {
            $hasil[$kategori] = array_values(array_filter(
                $tahap,
                fn ($key) => ! config("recruitment.stages.{$key}.bulk_only", false)
            ));
        }

        return $hasil;
    }

    // Tahap yang sah dituju untuk satu kategori lowongan: urutan pipeline kategori
    // itu, ditambah tahap universal (accepted/rejected) yang berlaku di mana saja.
    //
    // Ini daftar yang sama dengan isi dropdown "Pindah ke Tahap" -- dipakai juga
    // untuk validasi, supaya yang tidak ada di dropdown juga ditolak server.
    public static function selectableFor(?string $kategori): array
    {
        $pipeline = config('recruitment.pipelines.'.$kategori, []);

        $daftar = array_merge($pipeline, static::universalDestinations());

        // Tahap ber-'bulk_only' dibuang: tahap seperti itu butuh isian sekelompok
        // pelamar sekaligus, jadi hanya masuk akal lewat Update Massal. Kalau ikut
        // muncul di modal satu pelamar, statusnya bisa berubah tanpa kelengkapan
        // yang dibutuhkan -- keadaan setengah jadi yang tidak ada jalan keluarnya.
        return array_values(array_filter(
            $daftar,
            fn ($key) => ! config("recruitment.stages.{$key}.bulk_only", false)
        ));
    }

    // Daftar berkas blade berisi isian tambahan per tahap, buat disertakan di modal
    // "Pindah ke Tahap". Tahap yang tidak punya kunci 'form' berarti tidak butuh
    // isian apa pun -- jadi tidak ada berkas yang perlu dibuat untuknya.
    public static function formPartials(): array
    {
        $daftar = [];

        foreach (config('recruitment.stages', []) as $key => $definisi) {
            if (! empty($definisi['form'])) {
                $daftar[$key] = $definisi['form'];
            }
        }

        return $daftar;
    }

    // Isian yang diteruskan ke template email untuk tahap ini. Daftar kolomnya
    // ada di kunci 'mail_data' pada config/recruitment.php.
    public static function mailData(string $key, array $input = []): array
    {
        $kolom = config("recruitment.stages.{$key}.mail_data", []);

        return array_intersect_key($input, array_flip($kolom));
    }
}
