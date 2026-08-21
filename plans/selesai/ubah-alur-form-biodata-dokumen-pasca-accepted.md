# Ubah Alur Form Biodata: Dokumen Dipisah, Wajib Baru Pas Accepted

## Konteks

Form biodata pelamar sekarang 5 tab (Personal, Keluarga, Pendidikan, Pengalaman, **Dokumen**) dalam 1 form tunggal — submit sekali di akhir (tombol submit muncul di step 5), dan 4 dokumen (foto, CV, KTP, ijazah) wajib diisi di awal sebelum bisa submit sama sekali.

Perubahan yang diminta: dokumen **tidak lagi wajib di awal**. Pelamar cukup isi 4 tab pertama (Personal, Keluarga, Pendidikan, Pengalaman), submit dari situ, langsung dianggap selesai (`profile_completed = true`) dan bisa langsung apply ke lowongan. Dokumen baru jadi wajib **setelah** salah satu lamaran (`Application`) pelamar itu di-set status `accepted` oleh admin — dan halaman upload dokumennya dipisah total dari form biodata (bukan tab ke-5 di alur SPA lagi), diakses lewat halaman & route sendiri, dipicu dari notifikasi persisten di dashboard.

Konfirmasi yang sudah disepakati pas diskusi:
- Status source: **`Application.status = 'accepted'`** (bukan `Applicant.status` — kolom itu cuma `active`/`draft`, tidak dipakai buat alur seleksi; nilai lama `Pending`/`Reviewed`/`Accepted` dari seeder sudah mati, tidak dipakai kode aktif).
- Setelah submit 4 step: `profile_completed = true`, bisa langsung apply — dokumen bukan syarat apply.
- Karena dokumen nempel ke `Applicant` (bukan per `Application`), begitu **satu saja** lamaran pelamar itu `accepted`, dokumen jadi wajib untuk akun itu — tidak peduli status lamaran lain.
- Notifikasi: banner **persisten** (bukan session-flash yang hilang), mengikuti pola yang sudah ada di `applicant/dashboard.blade.php` (badge `profile_completed` amber/hijau) — tapi kali ini dalam bentuk banner + tombol yang mengarah ke halaman dokumen terpisah, dan tidak hilang sampai dokumen lengkap.

## File yang relevan (sudah dicek)

- `resources/views/applicant/profile/create.blade.php` & `edit.blade.php` — form 5 tab, Alpine state `step`, `isStepComplete()`, `validateAndSubmit()` (loop step 1-5), tombol submit muncul di `step === 5`.
- `app/Http/Controllers/Applicant/ProfileController.php` — `store()` (handle semua termasuk file upload dokumen, hardcode `documents_completed => true` & `biodata_progress => 100`), `saveDraft()` (autosave, sengaja skip dokumen & minat), `create()`/`edit()`/`show()`.
- `app/Models/Applicant.php` — flag `*_completed`, `calculateProgress()`, relasi `applications()`, `documents()`.
- `app/Models/Application.php` — relasi ke `Applicant`, `Admin\Lowongan`.
- `app/Http/Controllers/Admin/ApplicantController.php::updateStage()` — satu-satunya tempat `Application.status` diubah jadi `accepted`.
- `resources/views/applicant/dashboard.blade.php` — pola badge status persisten yang mau ditiru buat banner baru.
- `database/migrations/2025_12_29_071624_create_applicant_documents_table.php` — tabel `applicant_documents` (kolom `type`, `file_path`, `is_required` — `is_required` sekarang tidak pernah di-set, tetap dead column, tidak disentuh perubahan ini).
- `routes/web.php` — group `applicant.` prefix, middleware `auth`.

## Rencana Implementasi

### 1. Form biodata jadi 4 step, submit pindah ke step 4

Di `create.blade.php` & `edit.blade.php`:
- Array label tab (`['Personal', 'Keluarga', 'Pendidikan', 'Pengalaman', 'Dokumen']`, dipakai di 2 tempat: JS array di `validateAndSubmit()` dan `x-for` header tab) → buang `'Dokumen'`, jadi 4 item.
- Hapus blok `<div x-show="step === 5" ...>` (seluruh section upload dokumen, termasuk sub-komponen `validateFile()`) dari file ini — dipindah ke view baru (poin 3).
- `validateAndSubmit()`: loop `for (let s = 1; s <= 5; s++)` → `<= 4`.
- Tombol navigasi: `x-if="step < 5"` (tombol "Lanjutkan") → `step < 4`; `x-if="step === 5"` (tombol submit) → `step === 4`.
- `autosaveDraft()` sudah cuma kirim `_step_complete` untuk step 1-4 (tidak pernah nyentuh step 5/dokumen) — **tidak perlu diubah**.

### 2. Backend `ProfileController::store()` — dokumen jadi opsional, bukan bagian wajib submit awal

- Validasi file (`doc_foto`, dst) **sudah** `nullable` di backend (baris 64-72) — cukup dibiarkan, tidak perlu diubah. Yang wajib cuma dihapus di frontend (poin 1) — HTML `required` di step 5 dulu sekarang pindah ke halaman baru (poin 3).
- Hapus `'documents_completed' => true` dari array `updateOrCreate` di `store()` (baris ~92) — jangan di-hardcode lagi, karena submit awal sekarang tidak menyentuh dokumen sama sekali. `biodata_progress => 100` **tetap** di-hardcode seperti sekarang (submit 4 step tetap dianggap "selesai" secara biodata, terlepas dari dokumen).
- Blok upload dokumen di `store()` (baris 99-114, `foreach ($docs as $doc) ...`) **boleh tetap ada** — kalau kebetulan ada file ke-attach dari request lain, tetap ke-handle. Tapi sekarang harus manggil helper baru (poin 4) buat recompute `documents_completed` di akhir transaksi, bukan hardcode `true`.

### 3. Halaman baru: upload dokumen pasca-accepted (terpisah dari SPA form)

Controller baru `App\Http\Controllers\Applicant\DocumentController`:
- `edit()` — GET, tampilkan form upload (isi sama seperti step 5 lama: 4 field wajib foto/CV/KTP/ijazah + 5 field pendukung opsional). Load `$applicant->documents` biar tau dokumen mana yang sudah ada (field yang sudah ada dokumennya tidak di-`required` lagi, sama seperti pola `edit.blade.php` sekarang: `{{ isset($applicant) ? '' : 'required' }}`).
- `update()` — POST, validasi `doc_foto`/`doc_cv`/`doc_ktp`/`doc_ijazah` **required kalau belum ada** di DB (pakai `Rule::requiredIf` atau cek manual sebelum validate), sisanya `nullable` seperti sekarang. Simpan pakai logic yang sama seperti blok upload di `ProfileController::store()` (replace-if-exists per type), lalu panggil helper recompute (poin 4).

View baru `resources/views/applicant/profile/documents.blade.php` — reuse isi blok step 5 yang dihapus dari `create.blade.php` (styling & struktur sama), tapi jadi halaman standalone (form sendiri, bukan bagian dari Alpine multi-step).

Route baru di `routes/web.php`, dalam group `applicant.` yang sudah ada (`middleware(['auth'])`):
```php
Route::get('/documents', [DocumentController::class, 'edit'])->name('documents.edit');
Route::post('/documents', [DocumentController::class, 'update'])->name('documents.update');
```
Tidak digembok middleware `applicant.complete` atau status accepted — halaman ini boleh diakses kapan pun profil sudah ada (assumption: tidak perlu diblokir sebelum accepted, cukup tidak dipromosikan/tidak muncul banner-nya sampai accepted).

### 4. Helper: kapan dokumen "lengkap" & kapan banner nongol

Di `App\Models\Applicant`, tambah 2 method:
```php
public function hasAcceptedApplication(): bool
{
    return $this->applications()->where('status', 'accepted')->exists();
}

public function needsDocumentSubmission(): bool
{
    return $this->hasAcceptedApplication() && ! $this->documents_completed;
}

public function recalculateDocumentsCompleted(): void
{
    $required = ['foto', 'cv', 'ktp', 'ijazah'];
    $have = $this->documents()->whereIn('type', $required)->pluck('type')->unique();
    $this->documents_completed = $have->count() === count($required);
    $this->save();
}
```
Dipanggil dari `DocumentController::update()` (dan opsional dari `ProfileController::store()`, ganti hardcode `true` yang dihapus di poin 2) setelah proses simpan file selesai.

### 5. Modal notifikasi di dashboard

> Rencana awal poin ini "banner". Saat eksekusi diganti jadi **modal yang tidak bisa ditutup** atas permintaan user (eksplisit: "jangan ada 'nanti saja'").

`resources/views/applicant/dashboard.blade.php` — modal overlay muncul kalau `$applicant?->needsDocumentSubmission()`:
- Isi: "Selamat, lamaran Anda diterima!" + 1 tombol "Lengkapi Dokumen Sekarang" ke `route('applicant.documents.edit')`.
- **Sengaja tidak ada jalan keluar**: tanpa tombol X, tanpa "Nanti Saja", Escape & klik di luar tidak menutup. Satu-satunya cara lanjut adalah mengisi dokumen.
- Karena render-nya langsung dari kondisi DB (bukan session flash), otomatis muncul lagi tiap dashboard dibuka, dan otomatis hilang begitu `documents_completed` jadi `true` — tidak butuh mekanisme dismiss/tracking terpisah.

`DashboardController::index()` tidak perlu berubah — `$applicant` yang sudah di-pass ke view cukup buat panggil method baru di atas langsung dari Blade.

### 6. Daftar dokumen (ditentukan setelah plan awal ditulis)

Daftar final 13 dokumen, dikelompokkan 4 bagian, terpusat di konstanta `Applicant::DOCUMENT_GROUPS` (`*` = wajib):

| Kelompok | Dokumen |
|---|---|
| Identitas & Kependudukan | `*`Pas Foto, `*`KTP, `*`Kartu Keluarga, `*`Akta Kelahiran |
| Pendidikan & Lamaran | `*`Ijazah & Transkrip, `*`CV, Surat Lamaran *(jika CV belum lengkap)* |
| Keuangan & Perpajakan | `*`Buku Rekening BCA halaman pertama, NPWP |
| Dokumen Pendukung | Surat Nikah *(jika sudah menikah)*, Surat Keterangan Kerja *(jika punya pengalaman)*, BPJS Kesehatan, BPJS Ketenagakerjaan |

Total 7 wajib, 6 opsional. Konstanta ini jadi **satu sumber kebenaran**: aturan validasi + limit ukuran di `DocumentController`, field di view upload, dan `recalculateDocumentsCompleted()` semuanya baca dari sana. Halaman "Lampiran Berkas" di `admin/applicants/show.blade.php` & `applicant/profile/show.blade.php` juga diubah supaya ikut daftar itu (sebelumnya label-nya hardcode, jadi tipe dokumen baru tidak akan tampil sama sekali).

Keputusan turunan saat menyusun daftar:
- **BPJS dipecah 2 field** (Kesehatan & Ketenagakerjaan) walau di daftar user ditulis 1 baris — supaya cocok dengan data lama yang sudah pakai 2 tipe terpisah (`bpjs_kes`, `bpjs_tk`).
- **SIM & "Lainnya" dihapus** dari form karena tidak ada di daftar baru. Data lama tidak dihapus, cuma tidak diminta lagi.
- **Pas Foto & CV dipertahankan** sebagai wajib (tidak ada di daftar user) karena `foto` dipakai sebagai foto profil di PDF & halaman detail pelamar, dan `cv` muncul sebagai baris "CV: Tersedia/Tidak" di PDF — kalau dihapus, dua tampilan itu jadi kosong.

- **Scan Sertifikat Vaksin** (item no. 11 di daftar asli) — **dibatalkan**, diputuskan tidak diperlukan. Jangan ditambahkan lagi.

## Yang sengaja TIDAK disentuh (di luar scope)

- Email `AcceptedEmail` / template `emails.accepted_*` — tidak ditambah link ke halaman dokumen. Kalau nanti mau, itu perubahan terpisah.
- `EnsureApplicantProfileCompleted` middleware — tidak berubah, tetap cuma cek `profile_completed`, konsisten sama keputusan "dokumen bukan syarat apply".
- Kolom `is_required` di `applicant_documents` — tetap dead column seperti sekarang, tidak dipakai buat drive logic wajib (status wajib ditentukan dari `Applicant::DOCUMENT_GROUPS`, bukan dari kolom ini).
- ~~Data pelamar lama yang sudah submit lengkap (termasuk dokumen) sebelum perubahan ini — `documents_completed` mereka sudah `true` dari alur lama, tidak perlu backfill/migrasi data.~~
  **ASUMSI INI SALAH, dan sempat bikin fiturnya kelihatan tidak jalan.** Alur lama `ProfileController::store()` meng-hardcode `documents_completed => true` tiap kali biodata disubmit, **tanpa cek file benar-benar diunggah** — jadi flag itu false-positive untuk pelamar yang tidak pernah upload dokumen. Akibatnya `needsDocumentSubmission()` (yang cek `! documents_completed`) selalu `false`, dan modal tidak pernah muncul walau lamarannya sudah accepted.
  Diperbaiki lewat migration `2026_08_20_000001_backfill_documents_completed_on_applicants_table.php` yang menghitung ulang flag dari isi tabel `applicant_documents`. **Pelajaran: kolom boolean yang pernah di-set hardcode tidak bisa dipercaya sebagai sumber kebenaran — verifikasi ke data aktual dulu sebelum mengasumsikan data lama konsisten.**

## Checklist verifikasi (rencana awal — hasil aktualnya di bagian Status)

1. Isi form biodata baru sampai step 4 (Pengalaman), submit — cek redirect ke `applicant.profile.show`, DB: `profile_completed=1`, `biodata_progress=100`, `documents_completed=0` (tidak ada dokumen tersimpan).
2. Cek dashboard pelamar itu — bisa langsung apply ke lowongan (tombol "Cari Lowongan" tidak terkunci), **tidak ada** banner dokumen (belum ada lamaran accepted).
3. Apply ke 1 lowongan, admin ubah status lamaran itu jadi `accepted` lewat `ApplicantController::updateStage()`.
4. Refresh dashboard pelamar — banner "Lengkapi Dokumen" muncul, tombol mengarah ke `/applicant/documents`.
5. Buka halaman dokumen, submit tanpa isi 4 wajib — ditolak validasi, pesan jelas menyebut field mana.
6. Isi 4 dokumen wajib, submit — berhasil, DB: `documents_completed=1`, 4 baris baru di `applicant_documents`.
7. Refresh dashboard — banner sudah hilang.
8. Pelamar lain yang belum ada lamaran `accepted` coba akses `/applicant/documents` langsung via URL — halaman tetap bisa dibuka (assumption di atas), tapi dashboard-nya sendiri tidak nampilin banner.

## Status

**SELESAI — kode dieksekusi & sudah diverifikasi end-to-end di browser (21 Agustus 2026).**

File yang disentuh:
- `create.blade.php` & `edit.blade.php`: tab jadi 4, blok step 5 (upload dokumen) dihapus, `validateAndSubmit()` loop `<= 4`, tombol submit pindah ke `step === 4`.
- `ProfileController::store()`: `documents_completed` tidak lagi di-hardcode `true`, diganti panggilan `recalculateDocumentsCompleted()` di akhir transaksi.
- `Applicant`: konstanta `DOCUMENT_GROUPS` + helper `documentDefinitions()`, `requiredDocumentTypes()`, `hasAcceptedApplication()`, `needsDocumentSubmission()`, `recalculateDocumentsCompleted()`.
- `DocumentController` (baru) + view `applicant/profile/documents.blade.php` (baru) + route `applicant.documents.edit/update`.
- Modal di `applicant/dashboard.blade.php`.
- `admin/applicants/show.blade.php` & `applicant/profile/show.blade.php`: label lampiran berkas ikut `DOCUMENT_GROUPS`, tidak hardcode lagi.
- Migration `2026_08_20_000001_backfill_documents_completed_on_applicants_table.php` (baru).

**Tambahan di luar plan awal**: `Applicant::calculateProgress()` di-rebalance (35/15/25/25 = 100 tanpa dokumen). Kalau tidak, biodata 4 tab yang sudah lengkap cuma dihitung 85%, dan `saveDraft()` bakal menurunkan `biodata_progress` dari 100 ke 85 tiap kali pelamar buka-edit profilnya.

### Hasil verifikasi

Otomatis: `view:cache` sukses (semua Blade compile), `route:list` konfirmasi 2 route baru, `php -l` bersih. `sail artisan test` hasilnya sama persis sebelum & sesudah perubahan — 2 failed (`AuthenticationTest`, `RegistrationTest`), dikonfirmasi lewat `git stash` sebagai pre-existing, penyebabnya rule Turnstile di form login/register yang tidak dikirim oleh tes bawaan Breeze.

Manual di browser (akun `ClaudeUser@sosro.test`, lamaran di-set `accepted` sementara buat tes lalu dikembalikan ke `interview`):

| # | Yang dites | Hasil |
|---|---|---|
| 1 | Modal muncul saat lamaran accepted & dokumen belum lengkap | ✅ |
| 2 | Modal tidak bisa ditutup (Escape + klik di luar) | ✅ tetap bertahan |
| 3 | Tombol modal → `/applicant/documents` | ✅ |
| 4 | 13 dokumen tampil dalam 4 kelompok, wajib bertanda `*` | ✅ |
| 5 | Dokumen yang sudah ada: "Sudah ada file" + link Lihat, tidak dipaksa unggah ulang | ✅ |
| 6 | Submit dengan wajib kosong → diblokir browser | ✅ "Please select a file" |
| 7 | Validasi backend (file bukan gambar asli) | ✅ ditolak, pesan pakai label ramah, bukan `doc_kk` |
| 8 | Upload dokumen wajib yang kurang → sukses | ✅ toast berhasil, 7/7 tersimpan |
| 9 | Modal hilang setelah dokumen lengkap | ✅ |
| 10 | Form biodata tinggal 4 tab | ✅ |
| 11 | Tombol submit di tab 4 + autosave masih jalan | ✅ |

Data tes sudah dibersihkan: status lamaran dikembalikan ke `interview`, 3 file dummy dihapus dari DB & storage.

### Sisa (kosmetik, belum dikerjakan)

- Judul kelompok panjang ("IDENTITAS & KEPENDUDUKAN", "KEUANGAN & PERPAJAKAN") pecah jadi 2 baris di layar lebar. Fungsional, cuma kurang rapi.
