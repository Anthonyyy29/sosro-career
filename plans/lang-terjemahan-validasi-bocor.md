# Pesan Validasi Bocor Jadi Key Mentah (mis. "validation.unique")

## Konteks

Ditemukan pas cek fitur registrasi: kalau user daftar pakai email yang udah kepake, harusnya keluar pesan error yang enak dibaca ("Email ini sudah terdaftar" atau semacamnya), tapi yang keliatan di form (`resources/views/auth/register.blade.php:40`, lewat komponen `<x-input-error :messages="$errors->get('email')" />`) itu **key mentah**: `validation.unique`.

**Ini murni masalah bahasa/terjemahan, bukan bug logic** — validasi unique-nya sendiri jalan normal (email dobel beneran ditolak), cuma pesannya yang gagal diterjemahkan.

## Root cause

- `.env`: `APP_LOCALE=id` **dan** `APP_FALLBACK_LOCALE=id` — dua-duanya Indonesia.
- Project **tidak punya folder `lang/` sama sekali** — dicek, tidak ada `lang/id/validation.php` maupun `lang/en/validation.php` di level app manapun (cuma ada versi bawaan Laravel di `vendor/laravel/framework/.../lang/en/validation.php`, yang tidak otomatis kepakai kalau app-nya sendiri tidak punya file lang).
- Alurnya: Laravel coba terjemahin `validation.unique` ke locale `id` → gagal (file tidak ada) → coba fallback ke `id` juga (locale sama karena fallback_locale juga `id`) → gagal lagi → Laravel nyerah, balikin key mentahnya apa adanya.
- Dibuktikan lewat tinker: set locale `id` → keluar `validation.unique` (rusak). Set locale `en` → keluar `"The email has already been taken."` (normal, karena Laravel punya bawaan bahasa Inggris).

## Dampak

Bukan cuma di form registrasi — **semua pesan validasi bawaan Laravel** (required, email, min, max, confirmed, dst — kecuali yang sudah di-custom manual kayak `cf-turnstile-response.required` di `RegisteredUserController.php:39`) berpotensi bocor jadi key mentah di **semua form** di seluruh aplikasi, bukan cuma registrasi.

## Rencana perbaikan (2 langkah, disarankan dua-duanya)

1. **Tambah `lang/id/` yang beneran berisi terjemahan** — `validation.php`, `auth.php`, `passwords.php`, `pagination.php` minimal. Disarankan pakai package komunitas `laravel-lang/lang` (nyediain terjemahan Indonesia yang sudah lengkap & terverifikasi buat semua pesan bawaan Laravel) daripada nulis manual satu-satu (rawan typo/kurang lengkap). Ini fix yang "benar" karena UI aplikasi ini memang didesain berbahasa Indonesia dari awal.
2. **Ganti `APP_FALLBACK_LOCALE` dari `id` jadi `en`** — jaring pengaman. Kalau suatu saat ada key yang kelewat diterjemahkan (sekarang atau nanti pas Laravel nambah pesan baru), jatuhnya ke bahasa Inggris yang beneran ada isinya (bawaan Laravel), bukan balik nampilin key mentah kayak sekarang.

Kalau cuma langkah 1 tanpa langkah 2: tetap rawan balik ke masalah sama kalau ada key yang kelewat. Kalau cuma langkah 2 tanpa langkah 1: user Indonesia bakal lihat pesan error dalam bahasa Inggris — kurang pas buat aplikasi yang jelas didesain berbahasa Indonesia.

## Status

**Sudah dieksekusi, kedua langkah.** Sempat kejadian lagi bug yang sama (raw `validation.required` bocor pas submit form biodata) — konfirmasi ulang kalau ini emang nyerang seluruh app, bukan cuma registrasi.

1. `composer require laravel-lang/lang` + `php artisan lang:add id` → generate `lang/id/{validation,auth,passwords,pagination}.php` + `lang/id.json`.
2. `.env`: `APP_FALLBACK_LOCALE` diganti dari `id` ke `en`.

Diverifikasi lewat tinker: rule `required` & `unique` sekarang balikin kalimat Indonesia (`"Jk wajib diisi."`, `"Email sudah ada sebelumnya."`), bukan raw key lagi.

Catatan sampingan (di luar scope, gak diapa-apain): `composer require` sempat munculin 9 advisory security level "high" buat `phpoffice/phpspreadsheet` (dependency `maatwebsite/excel`, sudah ada dari sebelumnya, gak related ke perubahan ini) — worth di-follow-up terpisah kalau mau.
