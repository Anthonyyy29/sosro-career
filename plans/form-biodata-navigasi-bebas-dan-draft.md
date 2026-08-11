# Form Biodata: Navigasi Bebas + Simpan Progress (Kebutuhan 1 Selesai, Kebutuhan 2 Belum)

## Konteks

Form biodata pelamar (`applicant/profile/create.blade.php`) sekarang **linear murni**: 5 tab (Personal, Keluarga, Pendidikan, Pengalaman, Dokumen) dalam 1 `<form>` tunggal, submit sekali di akhir. Tombol "Lanjutkan Ke Tahap Berikutnya" (baris 789-816) blokir keras — tidak bisa pindah tab kalau field wajib di tab aktif belum keisi. Header tab (baris 54-62) cuma indikator visual, tidak bisa diklik buat lompat.

Ada 2 kebutuhan terpisah yang dibahas, jangan digabung jadi satu solusi (ini kesalahan awal yang sempat muncul di diskusi):

## Kebutuhan 1: Navigasi bebas, gate cuma di Submit

User ingin bisa pindah ke tab mana pun bebas (tidak linear), tapi tombol **Submit** tetap diblokir sampai semua field wajib di **semua 5 tab** terisi (bukan cuma tab aktif).

**Ini murni perubahan frontend (Alpine.js), tidak butuh backend/database sama sekali** — karena form tetap submit sekali di akhir seperti sekarang.

Yang perlu diubah di `create.blade.php` (dan `edit.blade.php` polanya sama):
1. Header tab (baris 54-62) — tambah `@click="step = index + 1"` biar bisa diklik lompat langsung
2. Tiap nomor tab dikasih indikator kelengkapan (titik merah/centang hijau) — dihitung live dari state Alpine, biar user tahu tab mana yang masih bolong walau lagi di tab lain
3. Gerbang validasi dipindah dari tombol "Lanjutkan" ke tombol "Submit" — loop `[required]` di **semua** 5 step (bukan cuma step aktif kayak sekarang)
4. **Gotcha penting**: field di tab yang tidak aktif itu `display:none` (`x-show`) — browser **tidak** akan validasi field hidden pas native HTML5 submit. Jangan andalkan `required` attribute browser buat validasi final, tetap harus custom JS yang cek semua step.

## Kebutuhan 2: "Remember form" — simpan progress / bisa lanjut nanti

Ini kebutuhan terpisah (opsional, belum diputuskan mau dikerjakan atau tidak). Ada 2 opsi, tergantung seberapa jauh yang diinginkan:

### Opsi 1 — Progress bar per section aja (ringan)
Kolom `personal_completed`, `family_completed`, `education_completed`, `experience_completed`, `documents_completed` **sudah ada** di tabel `applicants` tapi mati total (tidak pernah di-set, tidak pernah dibaca — lihat temuan sebelumnya, `calculateProgress()` di `Applicant.php` juga tidak pernah dipanggil). Tinggal wire up: tiap pindah tab, kirim AJAX kecil update flag itu. **Tidak perlu tabel baru.**

### Opsi 2 — Resume beneran lintas device/browser (lebih besar)
Kalau mau pelamar bisa nutup browser di HP, lanjut di laptop nanti: **jangan** bikin tabel staging terpisah (sempat dipertimbangkan, tapi ditolak — lihat alasan di bawah). Pakai pola `status = draft` vs `submitted`:
- Reuse kolom `applicants.status` (sekarang cuma pernah diisi `'active'`), tambah value `'draft'`
- Tiap pindah tab, autosave partial ke `applicant_profiles`/tabel anak langsung (field boleh masih null selama draft)
- Submit final = validasi penuh (seperti sekarang) + ganti status jadi `submitted`
- Tidak ada proses "copy dari tabel sementara ke tabel asli" yang rawan gagal — cuma 1 skema, bukan 2 yang harus selalu sinkron

**Kenapa bukan tabel staging terpisah**: sempat diusulkan (bikin tabel sementara, autosave ke situ tiap "Selanjutnya", baru pindah ke tabel asli pas submit final) — tapi ini **tidak menghilangkan** risiko atomicity yang mau dihindari, cuma memindahkan (proses "pindah dari staging ke tabel asli" itu sendiri butuh transaction juga). Ditambah beban maintain 2 skema yang harus selalu sinkron. Pola `status=draft` di tabel asli lebih simpel dan robust.

### localStorage vs server-side (Opsi 2)
Sempat dibahas: kenapa tidak simpan progress di `localStorage` browser aja (kayak form pada umumnya)? Karena form biodata ini **panjang** (15-30 menit, 5 section besar), pelamar realistis akan berhenti di tengah dan lanjut lain waktu — bisa beda device (HP → laptop) atau beda hari. `localStorage` tidak portable lintas device/browser, dan hilang kalau cache di-clear. Untuk kasus pemakaian ini, **server-side (Opsi 2) lebih cocok**, mirip alasan Google Form nyimpen di cloud bukan cuma di browser.

Opsional: `localStorage` masih bisa dipakai sebagai lapisan cepat **tambahan** (auto-save instan dalam 1 sesi/device yang sama, jaga-jaga tab kepencet close), di atas server-side sebagai sumber kebenaran utama — bukan pengganti.

## Status

Kebutuhan 1 (navigasi bebas) dan Kebutuhan 2 (remember/draft) itu independen, bisa dikerjakan terpisah, tidak harus dibarengin.

- **Kebutuhan 1 — SELESAI.** Sudah diimplementasi di `create.blade.php` & `edit.blade.php` (header tab bisa diklik, indikator kelengkapan per-tab, gerbang validasi dipindah ke tombol Submit lewat `validateAndSubmit()`), dikerjakan di branch `form-biodata-navigasi-draft`, sudah di-PR & merge ke `feature/normalisasi-database`.
- **Kebutuhan 2 (remember/draft) — belum dieksekusi.** Masih sebatas diskusi & rekomendasi (Opsi 1 ringan vs Opsi 2 resume lintas device, lihat di atas). Belum diputuskan mau dikerjakan atau tidak, dan opsi mana kalau iya.
