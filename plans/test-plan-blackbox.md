# Test Plan Black-Box — 3 Aktor (Applicant, Admin, Superadmin)

Checklist manual, isi kolom **Actual/Status** pas dites. Akun test yang dipakai:

| Aktor | Email | Password | Role/Cabang |
|---|---|---|---|
| Superadmin | ClaudeSuperAdmin@sosro.test | password123 | superadmin |
| Admin Cabang | ClaudeAdminCabang@sosro.test | password123 | admin, KPW Jawa Barat |
| Applicant | ClaudeUser@sosro.test | password123 | pelamar (belum ke-submit di form, cek ulang) |

Status: ✅ Pass · ❌ Fail · ⏳ Belum dites · ⚠️ Pass dengan catatan

> **HOLD — Cloudflare Turnstile**: form login `/admin/login` (dan kemungkinan `/login`, `/register`) pakai Cloudflare Turnstile (captcha). Ini gak boleh dan gak akan dicoba diselesaikan otomatis oleh Claude (kebijakan: gak boleh bypass/selesaikan CAPTCHA) — user yang harus centang/selesaikan Turnstile-nya secara manual tiap kali sebelum submit form login. Eksekusi checklist ini nunggu konfirmasi user tiap sesi login.

---

## 1. Admin Cabang (KPW Jawa Barat) — otorisasi & scoping

| # | Langkah | Expected | Actual/Status |
|---|---|---|---|
| A1 | Login `/admin/login` pakai ClaudeAdminCabang@sosro.test | Masuk ke dashboard, gak error | ✅ Masuk mulus, Turnstile auto-verify (dev), redirect ke `/admin/dashboard` |
| A2 | Buka Dashboard | Statistik yang tampil cuma punya KPW Jawa Barat, bukan lintas cabang | ✅ Semua 0 — cocok, cabang ini emang belum punya data (dicek ke DB: 4 lowongan total ada tapi 0 di cabang ini) |
| A3 | Buka `/admin/lowongan` (index) | Cuma nampilin lowongan cabang KPW Jawa Barat | ✅ "No data available" sebelum bikin lowongan baru — cocok, 4 lowongan yang ada di DB semua cabang lain |
| A4 | Buat lowongan baru | Berhasil, `cabang_id` otomatis ke-set ke KPW Jawa Barat (gak ada pilihan cabang lain / dipaksa) | ✅ Field cabang di form read-only nunjukin "KPW Jawa Barat", DB konfirmasi `cabang_id=4` |
| A5 | Toggle status lowongan cabang sendiri (aktif↔tidak aktif) | Berhasil | ✅ "Status lowongan berhasil diperbarui", tidak aktif→aktif |
| A6 | Coba akses/edit/toggle lowongan cabang **lain** (lowongan id=6, KPW Jakarta Banten) | 403 | ✅ `PATCH /admin/lowongan/6/toggle-status` → HTTP 403 "Dilarang" (dites via fetch JS di halaman, bukan cuma UI) |
| A7 | Buka `/admin/applicants` | Cuma pelamar yang melamar ke lowongan cabang sendiri | ✅ 0 pelamar (DB: 0 applications total juga, belum ada data buat benar2 uji filter-nya — lemah, perlu data lebih kaya nanti) |
| A8 | Buka `/admin/laporan` | Data cabang sendiri doang | ✅ Halaman load normal, 0 data (sama kayak A7, belum ada data buat uji filter beneran) |
| A9 | Buka `/admin/messages` (kontak) | Cuma pesan yang `cabang_id`-nya KPW Jawa Barat | ✅ "Belum ada pesan" (DB: 0 contacts total, sama seperti A7/A8) |
| A10 | Buka `/admin/users` (kelola akun admin) | **403** — admin biasa gak boleh akses | ✅ 403 "Maaf, halaman ini hanya untuk super admin." |

## 2. Superadmin — lintas cabang & kelola admin

| # | Langkah | Expected | Actual/Status |
|---|---|---|---|
| S1 | Login `/admin/login` pakai ClaudeSuperAdmin@sosro.test | Masuk ke dashboard | ✅ Masuk mulus, muncul menu tambahan "Pengguna" di sidebar yang gak ada buat admin cabang |
| S2 | Buka Dashboard | Statistik lintas semua cabang | ✅ "Total Lowongan Aktif: 2" — beda dari admin cabang yang cuma liat "1" (cabangnya sendiri), konfirmasi lintas cabang beneran |
| S3 | Buka `/admin/lowongan` | Nampilin lowongan dari SEMUA cabang | ✅ 5 lowongan tampil, dari HO/Jecoo/Claude Admin Cabang — bukan cuma superadmin sendiri (superadmin gak punya cabang) |
| S4 | Buat lowongan baru, pilih cabang bebas (bukan cabang tertentu) | Berhasil, `cabang_id` sesuai yang dipilih (dropdown cabang muncul, gak dikunci) | ✅ Field "Pilih Penempatan" beneran dropdown 22 pilihan (bukan readonly kayak admin cabang), pilih "Pabrik Sentul" → DB konfirmasi `cabang_id=18` |
| S5 | Toggle status / edit lowongan cabang manapun | Berhasil, gak ada 403 | ✅ Toggle lowongan "testty" (cabang Jecoo/Pabrik Cakung, bukan punya sendiri) → "Status lowongan berhasil diperbarui" |
| S6 | Buka `/admin/applicants` | Semua pelamar lintas cabang | ✅ Load normal, 0 data (belum ada applications sama sekali di DB — sama seperti A7, perlu data lebih kaya buat uji filter beneran) |
| S7 | Buka `/admin/laporan` | Data lintas cabang | ✅ Load normal, 0 data (sama seperti S6) |
| S8 | Buka `/admin/messages` | Semua pesan lintas cabang | ✅ "Belum ada pesan" (0 contacts di DB) |
| S9 | Buka `/admin/users` | Berhasil masuk (gak 403) | ✅ "Manajemen Pengguna", 19→20 data, list semua user (admin+pelamar) |
| S10 | Create admin baru dari `/admin/users`, assign role & cabang | Berhasil, akun baru muncul di list | ✅ "Berhasil! Akun Admin berhasil dibuat." DB konfirmasi `role=admin, cabang_id=3` (KPW Jakarta Banten) sesuai yang dipilih |

## 3. Applicant (ClaudeUser@sosro.test)

| # | Langkah | Expected | Actual/Status |
|---|---|---|---|
| U1 | Login `/login` | Masuk ke dashboard pelamar | ✅ Berhasil, dashboard nunjukin "Lowongan" & "Lamaran Saya" **terkunci** (profil belum lengkap) |
| U2 | Isi form biodata Tab 1 (Personal) dari kosong | Badge tab jadi hijau begitu semua field wajib keisi | ✅ Konfirmasi live fix `required` attribute kerja — badge merah "!" → hijau ✓ pas semua field wajib (nik, phone, jk, tempat/tanggal lahir, alamat, ex_employee, expected_salary, ready_dinas/placed_out, perokok, bertato) keisi |
| U3 | Upload 4 dokumen wajib (foto, CV, KTP, ijazah) di Tab 5 | Badge tab 5 jadi hijau | ✅ Upload 4 file dummy (jpg/pdf) via file input langsung, badge langsung hijau |
| U4 | Submit final ("Kirim Seluruh Data Lamaran") | Berhasil, redirect ke halaman profil, semua flag completion jadi true | ✅ "Profil dan dokumen berhasil disimpan." DB: `status=active profile_completed=1 biodata_progress=100`, semua 5 `*_completed=1`, 4 dokumen tersimpan |
| U5 | Autosave draft (sambil isi Tab 1) | Indikator "Tersimpan otomatis" muncul | ✅ Muncul di dekat tombol navigasi tab, konsisten sama temuan sesi sebelumnya |
| U6 | Apply ke lowongan (`Test Blackbox Admin Cabang`) | Berhasil, masuk ke "Lamaran Saya" | ✅ "Lamaran Anda ... telah berhasil dikirim!", status TERKIRIM |
| U7 | Coba lihat lowongan yang sudah dilamar lagi di listing | Tombol "Lamar" berubah jadi indikator "Sudah Dilamar", gak bisa diklik lagi | ✅ Tombol hijau "✓ Sudah Dilamar", gak ada cara apply ulang lewat UI |
| U8 | Apply ke 2 lowongan aktif lainnya (`testty`, `test banter`) | Berhasil, total lamaran jadi 3 | ✅ "Total Lamaran: 3" di halaman Lamaran Saya |
| U9 | Coba apply lowongan ke-4 (dites via `fetch POST /apply/{id}` langsung karena UI udah gak sisa tombol Lamar buat 3 lowongan aktif yang ada) | Ditolak, pesan rate limit jelas | ✅ HTTP 200 (redirect back), pesan **"Batas maksimal 3 lamaran dalam 14 hari telah tercapai."** — DB konfirmasi tetap 3 baris di `applications`, gak nambah jadi 4 |

**Catatan**: U9 dites lewat lowongan yang statusnya `tidak aktif` (gak muncul tombol Lamar di listing publik) untuk dapat target lowongan ke-4 yang belum pernah dilamar — server-side `ApplyController` sendiri ternyata tidak mengecek `status_lowongan` sama sekali (cuma UI listing yang filter aktif), jadi ini juga membuktikan urutan cek di controller: limit 3/14 hari dicek **sebelum** cek lowongan valid/aktif.
