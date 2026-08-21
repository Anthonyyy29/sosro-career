kata# Normalisasi Data Keluarga (Pilot) - Catatan untuk Next Update

## Konteks

Bagian dari diskusi soal normalisasi kolom JSON di `applicant_profiles` (`data_keluarga`, `pendidikan_formal`, `pendidikan_informal`, `pengalaman_kerja`, `minat`, `jenis_sim`). Diputuskan "Keluarga" jadi pilot pertama karena field-nya paling sederhana (7 kolom, seragam antara grup inti & kandung) dan **data yang ada sekarang kosong total** (`{"inti": [], "kandung": []}` di semua baris) — jadi risiko migrasi data untuk pilot ini nol.

Alasan kenapa mau dinormalisasi (bukan sekadar ganti-ganti tanpa alasan): kolom JSON secara desain tidak bisa di-query granular (misal filter/report "pelamar dengan anggota keluarga yang juga kerja di Sosro"), dan window sekarang paling aman buat pindah karena app belum live ke publik (masih di-hide) + data masih fresh.

Trade-off yang sudah disepakati/dipahami (jangan diulang tanya, sudah dibahas panjang):
- Setelah normalisasi, proses simpan (`ProfileController::store/update`) **wajib** dibungkus `DB::transaction()` — hapus baris lama + insert baris baru per anggota keluarga itu beberapa statement terpisah, bukan 1 UPDATE atomic seperti sekarang.
- View `create.blade.php` (form isi biodata) **tidak perlu berubah** — nama input (`k_inti[index][field]`, `k_kandung[index][field]`) tetap sama, cuma tujuan simpan di backend yang beda.
- View lain yang baca `data_keluarga` (edit.blade.php, show.blade.php, pdf.blade.php, admin applicants show/pdf) perlu diubah baca dari relasi baru.

## Skema yang disepakati

### Tabel baru: `applicant_family_members`
```
id
applicant_profile_id   → FK ke applicant_profiles.id, cascade delete
tipe                   → enum('inti', 'kandung')
nama                   → string, nullable
hubungan               → string, nullable
pendidikan             → string, nullable
tempat_lahir           → string, nullable
tgl_lahir              → date, nullable
pekerjaan              → string, nullable
hp                     → string, nullable
timestamps
```
Semua nullable karena tidak ada field keluarga yang `required` di form sekarang. Urutan tampil pakai `id` (urutan insert), tidak perlu kolom `urutan` terpisah.

### Model baru
`App\Models\ApplicantFamilyMember` — `belongsTo(ApplicantProfile::class)`.

Di `ApplicantProfile.php`: tambah relasi `familyMembers()` (`hasMany`). Filter inti/kandung cukup `->where('tipe', 'inti')` saat dipakai, tidak perlu 2 method relasi terpisah. Hapus `data_keluarga` dari `$fillable`/`$casts`.

### Migration
1. `create_applicant_family_members_table` — tabel baru + FK constraint.
2. `drop_data_keluarga_from_applicant_profiles_table` — setelah dipastikan aman (data lama kosong, tidak perlu script migrasi data terpisah untuk pilot ini).

### Controller — `ProfileController::store()` / method update (cek dulu nama method edit-nya saat eksekusi)
```php
DB::transaction(function () use ($profile, $request) {
    // ...simpan field applicant_profiles lain seperti biasa (tanpa key 'data_keluarga')

    $profile->familyMembers()->delete();
    foreach ($request->k_inti ?? [] as $item) {
        $profile->familyMembers()->create([...$item, 'tipe' => 'inti']);
    }
    foreach ($request->k_kandung ?? [] as $item) {
        $profile->familyMembers()->create([...$item, 'tipe' => 'kandung']);
    }
});
```

### View yang perlu disentuh
- `resources/views/applicant/profile/edit.blade.php` — inisialisasi state Alpine `keluargaInti`/`keluargaKandung` (sekarang baca `$applicant->profile->data_keluarga['inti']`/`['kandung']`), ganti baca dari `familyMembers` relasi, format ulang jadi array yang sama bentuknya.
- `resources/views/applicant/profile/show.blade.php`
- `resources/views/applicant/profile/pdf.blade.php`
- `resources/views/admin/applicants/show.blade.php`
- `resources/views/admin/applicants/pdf.blade.php`
- `create.blade.php` — **tidak perlu diubah**.

## Status

**SELESAI.** Tabel `applicant_family_members` sudah dibuat (`2026_08_10_000009`), kolom `data_keluarga` sudah di-drop (`2026_08_10_000010`), model `ApplicantFamilyMember` + relasi `familyMembers()` di `ApplicantProfile` sudah jalan, dan `ProfileController::store()`/`saveDraft()` sudah nulis ke tabel baru di dalam `DB::transaction()` sesuai rancangan. Commit: `b70b20c` (Fase 4).

Pola yang sama sudah diulang juga untuk JSON lain (pengalaman kerja, pendidikan formal/informal, minat) — lihat `normalisasi-struktur-database-prioritas.md`.
