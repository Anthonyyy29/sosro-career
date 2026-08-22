# Prioritas Normalisasi Struktur Database (Overview)

## Konteks

Analisis struktur database secara keseluruhan (bukan cuma keluarga — lihat `plans/normalisasi-keluarga-pilot.md` untuk pilot spesifik itu). Pertanyaan: dari semua tabel yang ada, mana yang "kepenuhan"/perlu dipecah, dan field mana aja yang perlu jadi tabel master/anak terpisah.

Tabel framework (`cache`, `cache_locks`, `failed_jobs`, `jobs`, `job_batches`, `migrations`, `password_reset_tokens`, `sessions`) tidak relevan — itu bawaan Laravel, sudah didesain benar.

## Tabel yang jelas kepenuhan: `applicant_profiles` (44 kolom)

Satu-satunya tabel yang beneran overload. Tabel lain (`users`, `admins`, `applicants`, `applications`, `contacts`, `applicant_documents`) semua ramping dan wajar, tidak perlu dipecah.

| Field sekarang | Masalah | Jadi tabel apa |
|---|---|---|
| `doc_foto, doc_cv, doc_ktp, doc_ijazah, doc_sim, doc_npwp, doc_bpjs_kes, doc_bpjs_tk, doc_lain` (9 kolom) | Repeating group klasik | `applicant_documents` — **sudah ada di skema, tinggal dipakai** |
| `data_keluarga` (JSON) | 1-ke-banyak (anggota keluarga) | `applicant_family_members`, kolom `tipe` (inti/kandung) — pilot sudah dirancang terpisah |
| `pendidikan_formal` + `pendidikan_informal` (2 kolom JSON) | Sama-sama riwayat pendidikan, cuma beda tipe | **Gabung jadi 1 tabel** `applicant_educations`, kolom `tipe` (formal/informal) — jangan bikin 2 tabel terpisah |
| `pengalaman_kerja` (JSON) | 1-ke-banyak riwayat kerja | `applicant_work_experiences` |
| `pendidikan_terakhir`, `jurusan`, `ipk` | Bukan soal normalisasi — kolom **mati**, sudah digantikan `pendidikan_formal` | Drop saja, tidak perlu dipindah ke mana pun |

## Temuan penting: `minat` (applicant_profiles) dan `bidang` (lowongan) itu vocabulary yang sama

Dicek dari data dump — isi `minat` (13 value: Marketing, HR & People Development, Sales & Distribution, General Affairs, Produksi/Teknik, Administrasi, Quality Control, Finance & Accounting, R&D, Internal Audit, Purchasing, IT, Supply Chain & Logistic) **sama persis** dengan daftar dropdown "Bidang" di form lowongan, cuma di-hardcode independen di 2 tempat beda. Kandidat kuat jadi 1 tabel master `job_fields`/`bidang`, dipakai bareng oleh minat pelamar & kategori lowongan — bukan didobel-tulis kayak sekarang.

## Tabel `lowongan` — bukan kepenuhan, tapi 4 field berstatus "seharusnya FK"

`kategori`, `bidang`, `tipe_lowongan`, `penempatan_cabang` — riwayat migration project ini nunjukin ini pernah didesain sebagai FK (`kategori_id` dst) terus di-drop total 8 hari kemudian, diganti string bebas. `bidang` makin kuat alasannya karena tumpang-tindih sama `minat` di atas (lihat poin sebelumnya).

**Keputusan final (lihat `db_sosro_normalized.dbml`, komentar poin 8): cuma 2 dari 4 field ini yang beneran jadi tabel master.** `bidang` → `job_fields` dan `penempatan_cabang` → `cabangs` jalan terus. `kategori` dan `tipe_lowongan` **dibatalkan** jadi tabel master (`job_categories`/`job_types`) — dikembalikan jadi varchar biasa, karena cuma 3-4 nilai tetap, fixed vocabulary, kecil kemungkinan berubah, dan cuma dipakai di 1 tempat — tidak sepadan biaya JOIN-nya dibanding `cabangs` (datanya bisa berubah, buka/tutup cabang, sudah ada bukti bug duplikasi string) atau `job_fields` (dipakai bareng 2 entitas: lowongan & minat pelamar).

**⚠️ Dependency yang wajib diikut pas eksekusi `penempatan_cabang` → `cabangs`:** scoping admin-per-cabang yang sudah live di `LowonganController.php` (index/store/update/destroy) sekarang bandingin **string** (`Auth::user()->cabang === $lowongan->penempatan_cabang`). Begitu kolom ini diganti jadi FK (`cabang_id`), perbandingan string itu **wajib ikut diubah** ke perbandingan `cabang_id` di request yang sama — kalau kelewat, semua admin bakal ke-block akses lowongan cabangnya sendiri (teks lama vs ID baru tidak akan pernah cocok), tanpa error yang kelihatan. Sama juga berlaku ke `admins.cabang` (string) yang dipakai sebagai sisi pembanding satunya.

## Level rendah / opsional — jangan dikerjain dulu

- `jenis_sim` (JSON array kode SIM) — nilai bisnis kecil buat dinormalisasi, tidak ada atribut tambahan selain kodenya sendiri
- `alamat`/`domisili` (text bebas) — bisa dipecah jadi kelurahan/kecamatan/kota/provinsi/kode_pos terstruktur, tapi ini proyek besar sendiri (butuh data wilayah Indonesia), cuma worth dikerjain kalau nanti butuh filter pelamar per kota/provinsi

## Belum krusial — didiskusikan tapi ditunda: `contacts.admin_id` vs `cabang_id`, dan alur balas pesan

Pertanyaan yang muncul: kenapa `contacts` (tabel pesan kontak dari pengunjung web) nyambung ke `admin_id` (FK ke `admins`), bukan `cabang_id`? Sudah dicek langsung ke `ContactController.php` + `admin/kontak/index.blade.php`:

- `admin_id` di sini merepresentasikan **penugasan ke 1 admin spesifik** (superadmin assign manual lewat dropdown di `assign()`), bukan sekadar "cabang mana yang pegang". Kalau diganti jadi `cabang_id`, informasi "siapa orangnya" hilang total dan tidak bisa direkonstruksi lagi (1 cabang bisa berisi banyak admin — relasinya ambigu ke arah situ). Sebaliknya, dari `admin_id` tetap bisa didapat cabangnya lewat join (`admin_id → admins.cabang_id`). Jadi `admin_id` benar dipertahankan apa adanya, tidak perlu diganti ke `cabang_id`.
- Alur peran (dari blade, ada komentar eksplisit `FITUR TERUSKAN (Hanya Superadmin)` / `FITUR BALAS (Semua Role)` / `FITUR HAPUS (Semua Role - Tapi Admin hanya bisa hapus yang ditugaskan ke dia)`): superadmin **bukan cuma** dispatcher — mereka juga bisa langsung balas pesan (tombol balas via `mailto:` + `markAsReplied()` muncul untuk semua role). Yang eksklusif superadmin cuma "Teruskan/assign" ke admin cabang tertentu, dan hak hapus lintas-cabang (admin cabang cuma bisa hapus pesan yang ditugaskan ke dirinya).
- Catatan kecil (bukan bug, cuma observasi): route `kontak.mark-replied` cuma dijaga `auth:admin` di level middleware, tidak ada pengecekan `admin_id`/role di dalam method `markAsReplied()` itu sendiri — tapi ini konsisten sama desain UI-nya yang memang membuka tombol balas untuk semua role, jadi bukan celah.

Status: didiskusikan lengkap, **tidak krusial untuk sekarang**, jangan dikerjain dulu. Kalau nanti mau dibahas lagi, ini sudah cukup buat lanjut tanpa perlu re-analisis dari awal.

## Ringkasan prioritas (paling jelas → paling opsiona)

1. Dokumen → `applicant_documents` (skema sudah siap)
2. `minat` + `bidang` lowongan → 1 tabel master bersama
3. `penempatan_cabang` (lowongan) → tabel master `cabangs`. `kategori` & `tipe_lowongan` **tidak** jadi tabel master — tetap varchar (fixed vocabulary 3-4 nilai, tidak sepadan biaya JOIN)
4. `data_keluarga`, `pendidikan_formal`+`pendidikan_informal` (gabung), `pengalaman_kerja` → tabel anak per applicant_profile
5. `jenis_sim`, `alamat`/`domisili` → opsional, tunda

## Perbandingan trade-off: Struktur Lama vs Struktur Baru (rencana)

| Aspek | Struktur Lama (sekarang) | Struktur Baru (rencana) |
|---|---|---|
| Query/laporan granular | Tidak bisa — data terkubur di JSON, harus load semua lalu filter di PHP | Bisa langsung `WHERE`/`JOIN` di SQL (misal filter pelamar S1, cari lowongan per kategori) |
| Validasi struktural dari DB | Tidak ada — JSON bisa berisi apa saja, DB tidak menolak bentuk yang salah | Ada — tiap kolom bertipe jelas, bisa dikasih constraint per field |
| Konsistensi cabang/kategori/bidang | Rawan drift — sudah kebukti (KPW Sumut NAD, `minat` vs `bidang` dobel) | Satu sumber kebenaran (tabel master) — ganti nama 1 tempat, semua ikut |
| Nambah jenis dokumen baru | Butuh migration (nambah kolom `doc_*` baru) | Tinggal insert row baru ke `applicant_documents`, tanpa migration |
| Nambah field baru ke sub-form (misal field baru di riwayat kerja) | Tidak perlu migration — tinggal tambah key JSON di frontend | **Butuh migration** setiap kali — kelemahan baru yang tidak ada di struktur lama |
| Kompleksitas kode simpan data | Simpel — 1 `updateOrCreate()`, atomic otomatis | Lebih rumit — hapus+insert ke beberapa tabel, **wajib** `DB::transaction()` manual |
| Risiko baca data (N+1 query) | Tidak ada — 1 query, semua ikut dalam 1 baris | Ada risiko kalau lupa eager-load relasi di salah satu dari banyak tempat baca |
| Lebar tabel `applicant_profiles` | 44 kolom, berat, campur banyak concern | ~29 kolom, lebih ramping, per-concern sudah keluar ke tabel sendiri |
| Risiko migrasi data | N/A (tidak ada migrasi) | **Rendah sekarang** (data masih fresh, belum live publik) — naik terus kalau ditunda |
| Effort development sekarang | Nol — sudah jadi | Butuh kerjaan nyata: migration + model + controller + ubah beberapa view |

**Kesimpulan**: struktur lama menang di *kesederhanaan simpan* dan *fleksibilitas nambah field tanpa migration*. Struktur baru menang di *kemampuan query/laporan*, *konsistensi data*, dan *integritas*. Tidak ada yang "menang total" — ini pertukaran antara **kecepatan development & fleksibilitas** (struktur lama) vs **kemampuan query & keandalan data jangka panjang** (struktur baru). Karena app belum live publik dan data masih fresh, baris "risiko migrasi data" sedang di titik paling murah — itu alasan utama kenapa sekarang waktu yang tepat kalau memang mau jalan, bukan karena struktur baru otomatis lebih baik di semua aspek.

## Detail: `job_fields` dan `applicant_job_field_interests`

Dua tabel baru ini pasangan buat gantiin kolom `minat` (JSON) di `applicant_profiles`, sekaligus nyambung ke `bidang` di `lowongan`.

**`job_fields`** — tabel master/kamus, isinya 13 bidang pekerjaan baku (Marketing, Finance & Accounting, HR & People Development, IT, dst). Satu sumber kebenaran buat daftar yang sekarang di-hardcode dobel: dropdown "Bidang" di form lowongan, dan urutan minat di form biodata pelamar — isinya sama persis, cuma ditulis manual 2 kali di tempat berbeda.

**`applicant_job_field_interests`** — tabel pivot many-to-many antara `applicant_profiles` dan `job_fields`.

### Koreksi penting: bukan cuma "minat" biasa, tapi drag-to-rank

UI aslinya (`applicant/profile/create.blade.php:275-309`) itu **drag-to-rank pakai SortableJS** atas SEMUA 13 `job_fields` sekaligus — bukan checklist pilih sebagian. Dikonfirmasi dari `ProfileController.php`:
```php
'minat_ordered' => 'required|array|min:13',   // wajib semua 13, bukan sebagian
$profileData['minat'] = $request->minat_ordered; // urutan array = urutan ranking
```
Jadi tabel pivotnya wajib ada kolom `rank`:
```dbml
Table applicant_job_field_interests {
  applicant_profile_id bigint [ref: > applicant_profiles.id]
  job_field_id bigint [ref: > job_fields.id]
  rank int [note: '1 = paling diminati, 13 = paling kurang diminati']

  indexes {
    (applicant_profile_id, job_field_id) [pk]
  }
}
```
Konsekuensinya: tiap pelamar akan **selalu punya persis 13 baris** di tabel ini (bukan bervariasi), karena semua bidang wajib diranking. Ini trade-off yang disengaja — 13 baris per pelamar demi bisa query granular ke data ranking, dibanding 1 kolom JSON yang kompak tapi tidak bisa di-query.

### Contoh konkret (data asli dari dump, `applicant_profiles` id=4)

Urutan minat asli pelamar ini: `["Information Technology", "Research & Development", "Marketing", "Sales & Distribution", "General Affairs", "Human Resources & People Development", "Produksi / Teknik", "Administrasi", "Quality Control", "Finance & Accounting", "Internal Audit", "Purchasing", "Supply Chain & Logistic"]` — pelamar ini beneran nge-drag "Information Technology" ke rank 1 (default posisinya rank 12) dan "Research & Development" ke rank 2 (default rank 9).

Hasil di tabel pivot (job_field_id mengikuti urutan default `$minat_list` di kode: 1=Marketing, 2=HR & People Dev, 3=Sales & Distribution, 4=General Affairs, 5=Produksi/Teknik, 6=Administrasi, 7=Quality Control, 8=Finance & Accounting, 9=R&D, 10=Internal Audit, 11=Purchasing, 12=Information Technology, 13=Supply Chain & Logistic):

| applicant_profile_id | job_field_id | rank |
|---|---|---|
| 4 | 12 (Information Technology) | 1 |
| 4 | 9 (Research & Development) | 2 |
| 4 | 1 (Marketing) | 3 |
| 4 | 3 (Sales & Distribution) | 4 |
| 4 | 4 (General Affairs) | 5 |
| 4 | 2 (Human Resources & People Development) | 6 |
| 4 | 5 (Produksi / Teknik) | 7 |
| 4 | 6 (Administrasi) | 8 |
| 4 | 7 (Quality Control) | 9 |
| 4 | 8 (Finance & Accounting) | 10 |
| 4 | 10 (Internal Audit) | 11 |
| 4 | 11 (Purchasing) | 12 |
| 4 | 13 (Supply Chain & Logistic) | 13 |

### Manfaat query yang dibuka

Dengan bentuk ini, query seperti "siapa aja yang taruh IT sebagai minat #1?" tinggal:
```sql
SELECT applicant_profile_id FROM applicant_job_field_interests
WHERE job_field_id = 12 AND rank = 1;
```
Dengan `minat` masih JSON seperti sekarang, ini **mustahil** dilakukan lewat SQL — harus load semua baris `applicant_profiles`, decode JSON satu per satu di PHP, baru bisa cari siapa yang taruh IT di posisi pertama.

## Status

**SELESAI** untuk seluruh item prioritas 1-4. Yang sudah dieksekusi (migration `2026_08_10_000002` s/d `000021`, commit Fase 0-8):

1. Dokumen → `applicant_documents` aktif, 9 kolom `doc_*` di-drop (`000008`).
2. `minat` → tabel master `job_fields` + pivot `applicant_job_field_interests` dengan kolom `rank` (`000003`, `000016`, `000017`).
3. `penempatan_cabang` → tabel master `cabangs` + `cabang_id` di `lowongan`/`admins` (`000002`, `000004`, `000005`, `000006`, `000007`). Scoping admin-per-cabang ikut diubah dari perbandingan string ke `cabang_id` — dependency yang diwanti-wanti di atas sudah ditangani.
4. `data_keluarga`, `pengalaman_kerja`, `pendidikan_formal`+`informal` → tabel anak per `applicant_profile` (`000009` s/d `000015`). Catatan: pendidikan formal & informal akhirnya jadi **2 tabel terpisah**, bukan 1 tabel gabungan seperti rencana awal di dokumen ini.

Pengecualian yang sengaja dibatalkan/di-revert saat eksekusi:
- `lowongan.bidang` **dikembalikan jadi string biasa**, tidak jadi FK ke `job_fields` (`000020`, commit `70816ee`) — jadi `job_fields` sekarang cuma dipakai sisi minat pelamar, tidak dipakai bareng seperti rencana awal.
- `kategori` & `tipe_lowongan` tetap varchar, sesuai keputusan yang sudah ditulis di atas.

Item prioritas 5 (`jenis_sim`, `alamat`/`domisili` terstruktur) **sengaja tidak dikerjakan** — sejak awal ditandai opsional & ditunda, bukan pekerjaan yang menggantung.

Lihat `db_sosro_normalized.dbml` untuk skema aktual hasil akhirnya (di-introspeksi langsung dari DB, bukan dari rencana ini).
