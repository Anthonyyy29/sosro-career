# Tahap Baru "Unconfirmed" & "Confirmed" sebelum Interview (khusus kategori Profesional)

## Konteks

Ide: sebelum tahap "Interview" di alur seleksi kategori **Profesional**, ditambah 2 gerbang baru:

1. Admin geser status kandidat ke **"Unconfirmed"** — sistem otomatis kirim email ke alamat email **interviewer** (orang di user department yang bakal ngewawancara kandidat — **bukan** akun/user di sistem ini, cuma alamat email biasa yang diketik manual sama admin pas ubah status).
2. Interviewer klik tombol **"Konfirmasi"** di email itu (gak perlu login/akun apapun) → status kandidat otomatis pindah jadi **"Confirmed"**.
3. Dari situ admin lanjut proses "Interview" seperti biasa (isi tanggal/link, dst — bagian ini gak berubah sama sekali).

**Khusus kategori Profesional dulu** — belum diputusin apa perlu juga buat Magang/Management Trainee, jadi 2 kategori itu gak disentuh buat sekarang.

**Status: belum dieksekusi, baru didiskusikan & di-plan.** User sengaja minta dicatat dulu di plan, belum minta diimplementasi.

## Kenapa ini nyambung ke refactor tahapan seleksi sebelumnya

Tahapan seleksi sekarang sumbernya tabel DB (`recruitment_stages` + `recruitment_stage_pipeline`, lihat commit "Migrasi Tahapan Seleksi ke Tabel DB"), bukan lagi hardcode di blade. Jadi nambah 2 tahap baru ke pipeline Profesional **otomatis muncul** di semua tempat yang udah ada (dropdown filter, dropdown "Pindah ke Tahap", badge warna/label) — gak perlu ubah blade buat bagian itu. Yang beneran baru cuma: form input email interviewer, dan mailable + link konfirmasi publik (fitur yang emang belum ada sama sekali di app ini sampai sekarang).

## Rencana Implementasi

### 1. Data tahap baru (lewat `RecruitmentStageSeeder.php` yang udah ada)
- Stage baru: `unconfirmed` (label "Unconfirmed", warna amber) dan `confirmed` (label "Confirmed", warna lime) — pilih warna yang belum kepake biar gak ketuker sama tahap lain yang udah ada.
- Pipeline Profesional diupdate urutannya: `administration → psikotes → unconfirmed → confirmed → interview → offering → mcu`. Karena pivot-nya pakai `updateOrCreate`, jalanin ulang seeder-nya aman (idempotent) — otomatis "geser" urutan interview/offering/mcu yang lama.
- Label sisi pelamar (opsional, biar lebih ramah dari "Unconfirmed"/"Confirmed" mentah): `unconfirmed` → "Menunggu Jadwal Interview", `confirmed` → "Interview Terjadwal".

### 2. Kolom baru: `applications.interviewer_email`
Migration nambah kolom nullable — nyimpen alamat email yang diketik admin (buat referensi/audit, dan biar halaman konfirmasi bisa nunjukin info itu).

### 3. Form input di modal "Ubah Status" (admin)
`resources/views/admin/applicants/index.blade.php` — tambah 1 panel baru (pola sama kayak panel Psikotes/Interview yang udah ada, `x-show="nextStatus === 'unconfirmed'"`), isinya 1 field: **Alamat Email Interviewer** (`type="email" required`).

### 4. Validasi + kirim email (`ApplicantController::updateStage()`)
- Rule baru: `'interviewer_email' => 'required_if:next_status,unconfirmed|nullable|email'`.
- Simpan `interviewer_email` ke `$updateData` sebelum `$application->update(...)`.
- Cabang baru di pemilihan Mailable: kirim ke `$request->interviewer_email` — **satu-satunya mailable di fungsi ini yang dikirim ke interviewer, bukan ke kandidat** (semua mailable lain sekarang dikirim ke email kandidat).

### 5. Mailable baru: `app/Mail/InterviewConfirmationRequestEmail.php`
Pola sama kayak `InterviewEmail.php` (view-based Mailable). Isinya: info kandidat + posisi, dan tombol **"Konfirmasi Kesediaan"** yang link-nya di-generate pakai `URL::temporarySignedRoute('interview.confirm', now()->addDays(7), ['application' => $application->id])` — link aman (signature diverifikasi Laravel, gak bisa ditebak/dipalsu), berlaku 7 hari, **gak butuh akun/login**.

### 6. Route + controller konfirmasi (publik, tanpa login)
- Route baru (area publik, sejajar `POST /apply/{lowongan}`): `GET /interview-confirmation/{application}`, middleware `signed`.
- Controller baru `InterviewConfirmationController::confirm()`: cek status aplikasi masih `unconfirmed` (jaga-jaga link basi/diklik 2x/admin udah geser manual) → kalau iya update jadi `confirmed` + tampilin halaman sukses; kalau enggak, tampilin pesan "link ini udah gak berlaku" (bukan error keras).
- View baru sederhana (pakai `x-guest-layout` biar branding konsisten) — cuma nampilin hasil, gak ada form.

## Verifikasi (pas nanti dieksekusi)

1. Cek `RecruitmentStage::pipelines()['Profesional']` via tinker — 7 tahap urut bener.
2. Browser: dropdown "Pindah ke Tahap" kategori Profesional otomatis nunjukin "Unconfirmed"/"Confirmed" di posisi yang bener **tanpa ubah blade buat dropdown-nya** (buktiin desain DB-driven-nya beneran kepakai).
3. Submit "Unconfirmed" tanpa isi email → ketolak validasi. Isi email valid → `interviewer_email` kesimpen + email beneran terkirim.
4. Buka link konfirmasi dari email (tanpa login) → status jadi `confirmed`. Buka link yang sama 2x → kedua kalinya "udah gak berlaku", bukan flip lagi/error 500.
5. Akses route dengan signature yang diotak-atik → 403 (default behavior middleware `signed`).
