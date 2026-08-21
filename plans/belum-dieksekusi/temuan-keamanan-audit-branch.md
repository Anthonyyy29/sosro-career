# Temuan Keamanan & Bug dari Audit Branch (kapan-kapan dieksekusi)

## Konteks

Hasil `/code-review high` pada 21 Agustus 2026 atas `git diff main...HEAD` (40 commit, ~5.4k baris) di branch `feature/autosave-hemat-tulis-db`. Cakupannya seluruh kerjaan sejak `main`: normalisasi DB (cabang/job_fields/recruitment_stages jadi FK, kolom JSON dipecah jadi tabel anak), pemindahan scoping admin dari `created_by` ke `cabang_id`, navigasi bebas form biodata + autosave, dan pemisahan upload dokumen.

**Hampir semua temuan berasal dari kerjaan normalisasi DB 10 Agustus**, bukan dari perubahan alur dokumen 20-21 Agustus. Tidak ada satu pun yang sudah diperbaiki — dokumen ini murni catatan supaya tidak hilang.

**Status: belum dieksekusi, sengaja ditunda.** User memutuskan dibereskan "kapan-kapan", bukan sekarang.

## Prioritas kalau nanti dikerjakan

Urutan saran: **#1 dan #2 dulu**. #2 khususnya, karena kerusakannya permanen begitu migrasi jalan di produksi — tidak bisa dibatalkan. #3 dan #4 saling menyambung dengan #2 dan sebaiknya dikerjakan sepaket.

---

## Sudah diverifikasi langsung ke kode & database

### 1. `UserController::update()` tanpa penjagaan superadmin — bisa naik pangkat sendiri

`app/Http/Controllers/Admin/UserController.php:90`

`index()` punya `abort(403, ...)` untuk non-superadmin, tapi `update()` tidak punya sama sekali — padahal menerima field `role` dan `password`. Route-nya (`PUT /admin/users/{id}`) cuma dibungkus `auth:admin`.

Akibatnya admin cabang mana pun bisa:
```
PUT /admin/users/{id_sendiri}  role=superadmin
```
untuk menaikkan dirinya jadi superadmin, atau mengganti password admin lain.

**Catatan riwayat:** di `ezra.md` pernah diputuskan `store()` & `destroy()` sengaja tidak diberi role-check sebagai risiko yang diketahui & diterima. `update()` beda kelas — yang ini jalur eskalasi hak akses, bukan sekadar akses data.

**Perbaikan:** tambahkan penjagaan yang sama seperti `index()`:
```php
abort_if(Auth::user()->role !== 'superadmin', 403);
```
Pertimbangkan juga meninjau ulang keputusan lama untuk `store()`/`destroy()`.

### 2. `penempatan_cabang` di-drop tanpa backfill ke `cabang_id` — kehilangan data permanen

`database/migrations/2026_08_10_000005_*` → `2026_08_10_000007_*`

Migrasi `000005` menambah kolom `cabang_id` (nullable, kosong). Migrasi `000007` langsung `dropColumn(['bidang', 'penempatan_cabang'])`. **Tidak ada satu pun baris backfill di seluruh folder migrasi** (sudah dicek dengan grep) — bandingkan `000020` yang justru melakukan backfill untuk `bidang`.

Di DB dev saat audit: 8 lowongan, `cabang_id` terisi semua, jadi tidak kelihatan. Kemungkinan datanya dibuat ulang setelah migrasi jalan.

**Bahayanya ada di deploy produksi**: semua lowongan lama jadi `cabang_id = NULL`, sementara string aslinya **sudah terhapus permanen dan tidak bisa dipulihkan**. Dampak turunannya: lowongan lama hilang dari daftar admin cabang (`where('cabang_id', ...)`), `$app->lowongan->cabang->nama` jatuh ke fallback "Kantor Pusat", filter lokasi di listing publik kosong.

**Perbaikan:** tambahkan migrasi backfill **sebelum** deploy ke produksi, mencocokkan `penempatan_cabang` (string) ke `cabangs.nama`. Kalau `000007` sudah terlanjur jalan di produksi, datanya tidak bisa dipulihkan dari DB — harus dari backup.

### 3. Perbandingan `!==` lolos saat kedua sisi NULL — 8 lokasi

`NULL !== NULL` bernilai `false`, jadi penjagaan `$user->role !== 'superadmin' && $x->cabang_id !== $user->cabang_id` **lolos** ketika dua-duanya NULL.

Kondisi nyata saat audit: **3 admin dengan `role = 'admin'` punya `cabang_id = NULL`** (dari 12 admin, 6 NULL). Begitu ada baris ber-`cabang_id` NULL (lihat #2 dan #4), ketiga admin itu bisa mengaksesnya.

Lokasi:
- `app/Http/Controllers/Admin/LowonganController.php:31` (toggleStatus), `:132` (update), `:156` (destroy)
- `app/Http/Controllers/Admin/ApplicantController.php:79` (show), `:92` (byLowongan), `:126` (updateStage), `:176` (downloadPdf)
- `app/Http/Controllers/ContactController.php:76` (destroy)
- `resources/views/admin/lowongan/index.blade.php:905` (kondisi tampilan tombol)

**Perbaikan:** tolak juga saat admin belum punya cabang:
```php
is_null($user->cabang_id) || $x->cabang_id !== $user->cabang_id
```
Pola ini sudah benar di `LowonganController::store()` (`abort_if(!Auth::user()->cabang_id, 403, ...)`) — tinggal disamakan ke 8 lokasi lain.

### 4. `cabang_id` superadmin tidak divalidasi — sumber baris NULL

`app/Http/Controllers/Admin/LowonganController.php:96`

```php
$cabangId = $request->cabang_id;   // diambil mentah
```
Validator hanya memeriksa `judul_lowongan` dan `tanggal_akhir`. Superadmin yang tidak mengisi cabang menghasilkan lowongan `cabang_id = NULL` — dan itulah pemicu #3. Id ngawur akan menabrak FK `cabangs` dan menghasilkan 500.

**Perbaikan:** `'cabang_id' => 'required|exists:cabangs,id'` di cabang superadmin, pada `store()` dan `update()`.

Sisi admin cabang sudah benar (403 kalau belum di-assign) — tidak perlu diubah.

---

## Dilaporkan review, BELUM diverifikasi manual

Perlu dicek dulu kebenarannya sebelum dikerjakan — agen review bisa keliru.

| # | Lokasi | Dugaan masalah |
|---|---|---|
| 5 | `Admin/ApplicantController.php:237` (`bulkProcess`, `bulkProcessInterview`) | Tidak ada scope cabang sama sekali, padahal `bulkUpdate` punya. Admin cabang bisa POST id lamaran cabang lain lalu memindahkannya ke psikotes/interview — sekaligus mengirim email undangan. Juga `$ids = $request->selected_ids` tidak divalidasi; kalau field hilang, `whereIn('id', null)` melempar error. |
| 6 | `Applicant/ProfileController.php:46` | `nik` dapat UNIQUE index dari migrasi `000018` tapi rule validasinya masih `required\|digits:16` saja. NIK kembar → `QueryException`/500 saat submit, transaksi rollback tanpa pesan ke user. Butuh `Rule::unique(...)->ignore(...)` karena `update()` memanggil ulang `store()`. |
| 7 | `Applicant/ProfileController.php:87` | `store()` menandai keempat tab lengkap + `biodata_progress = 100`, padahal rule validasinya cuma mencakup field tab 1 — tidak ada rule untuk `k_inti`, `pendidikan_formal`, `pengalaman_kerja`. POST langsung dengan field tab 1 saja menghasilkan `profile_completed = true` tanpa data keluarga/pendidikan/pengalaman, lalu lolos middleware `applicant.complete`. |
| 8 | `Applicant/ProfileController.php:182` (`saveDraft`) | Rule `digits:16` pada `nik`, `min:10` pada `phone`, `date` pada `tanggal_lahir` diterapkan di endpoint autosave — padahal endpoint itu justru dipanggil saat field masih setengah terisi. Hasilnya 422, transaksi batal, **kerjaan keempat tab hilang**, dan satu-satunya tanda cuma teks kecil "Gagal menyimpan draft". |
| 9 | `Admin/DashboardController.php:31` | `totalUsers`/`totalApplicants` untuk superadmin ikut tersaring `whereHas('applicant.applications.lowongan', ...)`, sehingga berubah makna jadi "user yang pernah melamar". Pelamar terdaftar yang belum melamar tidak terhitung. |
| 10 | `migrations/2026_08_10_000021_*` | `contacts.cabang_id` ditambah tanpa backfill dari `admin_id`. Pesan yang sudah diteruskan hilang dari kotak masuk penerimanya setelah deploy dan harus diteruskan ulang manual. |
| 11 | `ContactController.php:76` | Penjagaan hapus dilonggarkan dari superadmin-only jadi pencocokan `cabang_id` — yang kena bug NULL di #3. Admin tanpa cabang bisa menghapus permanen semua pesan ber-`cabang_id` NULL. Tombolnya juga benar-benar tampil di blade, jadi bukan cuma lewat request buatan. |

---

## Sudah dicek review dan dinyatakan aman

Tidak perlu diperiksa ulang: cache statis per-request di `RecruitmentStage` (aman di non-Octane), whitelist `Rule::in(RecruitmentStage::allKeys())` pada `updateStage`, penulisan ulang JSON→relasional di keempat blade PDF/show (tidak ada sisa referensi `data_keluarga`/`doc_*`/`->minat`), 22 opsi kota di halaman kontak cocok persis dengan `CabangSeeder`, `calculateProgress` tetap berjumlah 100, penghapusan `applicants.name` tidak berdampak.

Satu catatan kecil: `blankToNull()` di `ProfileController` sebenarnya redundan karena Laravel 11 sudah punya middleware `ConvertEmptyStringsToNull` secara default — tapi tidak berbahaya, jadi dibiarkan.
