# Form Kontak: Widget Turnstile Ada, Tapi Tidak Divalidasi Server

**Urgensi: SEDANG.**
Dampaknya terbatas (spam masuk ke tabel `contacts` — bukan kebocoran data, bukan eskalasi hak akses), tapi biaya perbaikannya sangat kecil: 2 baris. Rasio untung-biayanya bagus, tinggal tunggu waktu luang.

**Status: belum dieksekusi.** Ditemukan 23 Agustus 2026 sewaktu menelusuri `ContactController` untuk pertanyaan soal `cabang_id`.

---

## Masalah

Halaman `/kontak` **menampilkan** widget Turnstile — `resources/views/pages/kontak.blade.php:104-107`:

```blade
{{-- Turnstile Container --}}
<x-turnstile />
@error('cf-turnstile-response')
```

Pengunjung melihat kotak verifikasi, mencentangnya, browser mengirim token `cf-turnstile-response` bersama form.

Tapi `ContactController::store()` (baris 20-30) **tidak pernah memeriksa token itu**:

```php
$validated = $request->validate([
    'confirm_email_address' => 'prohibited',
    'name'    => 'required|string|max:100',
    'email'   => 'required|email:rfc,dns',
    'city'    => 'required',
    'message' => 'required|string|min:10|max:2000',
]);
```

Tidak ada rule `cf-turnstile-response` di sana, dan tidak ada `use ...Rules\Turnstile` di berkas itu.

## Kenapa terlewat

Baris `@error('cf-turnstile-response')` di blade membuatnya **terlihat** sudah divalidasi. Padahal error itu tidak akan pernah muncul — tidak ada kode yang membuatnya. Ini satu-satunya dari empat titik Turnstile yang bolong:

| Berkas | Widget di blade | Divalidasi server |
|---|---|---|
| `app/Http/Requests/Auth/LoginRequest.php:33` | ada | **ya** |
| `app/Http/Controllers/Auth/RegisteredUserController.php:37` | ada | **ya** |
| `app/Http/Controllers/Admin/Auth/AdminAuthController.php:25` | ada | **ya** |
| `app/Http/Controllers/ContactController.php:20` | ada | **TIDAK** |

## Akibatnya

Captcha hanya menghalangi orang yang mengisi form lewat browser. Bot tidak lewat browser — bot menembak `POST /kontak` langsung. Karena server tidak pernah menanyakan token, permintaan **tanpa** `cf-turnstile-response` sama sekali tetap diterima dan tersimpan ke `contacts`.

Perlindungan yang benar-benar bekerja sekarang tinggal **honeypot** (`confirm_email_address`, baris 16 dan 21) — efektif melawan bot generik yang mengisi semua field, tidak melawan bot yang dibuat khusus untuk form ini.

## Perbaikan

Samakan dengan tiga controller lain:

```php
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
```
```php
$validated = $request->validate([
    // ...rule yang sudah ada...
    'cf-turnstile-response' => ['required', new Turnstile()],
], [
    'cf-turnstile-response.required'  => 'Silakan centang verifikasi keamanan.',
    'cf-turnstile-response.turnstile' => 'Verifikasi keamanan gagal atau sesi habis. Silakan coba lagi.',
]);
```

Perhatikan: `cf-turnstile-response` **tidak boleh** ikut masuk ke `Contact::create($validated)` — kolomnya tidak ada di tabel. Karena `$fillable` di `App\Models\Contact` sudah membatasi ke 7 kolom, secara teknis aman, tapi lebih jelas kalau di-`unset` atau divalidasi terpisah dari `$validated`. Pola serupa sudah ada di `AdminAuthController` baris 33 (`$request->only('email', 'password')`) dengan komentar eksplisit soal ini.

## Catatan waktu pengerjaan

Saat ini `.env` memakai **dummy key Turnstile** (lolos otomatis) untuk keperluan debug — lihat komentar di `.env` sekitar baris 54. Jadi pengujian form kontak bisa dilakukan tanpa terhalang tantangan captcha. **Tapi verifikasi akhir harus diulang dengan key asli**, karena dummy key selalu meloloskan apa pun dan tidak membuktikan rule-nya benar-benar bekerja.

## Verifikasi (kalau nanti dieksekusi)

1. Kirim pesan lewat browser dengan captcha dicentang — berhasil, tersimpan di `contacts`.
2. Kirim lewat browser **tanpa** mencentang captcha — ditolak, pesan error muncul di halaman (bukan error 500).
3. POST langsung tanpa field `cf-turnstile-response` sama sekali — ditolak validasi, **tidak** ada baris baru di `contacts`.
4. Ganti sementara ke dummy key yang selalu gagal (`2x0000000000000000000000000000000AA`) — kiriman valid pun harus ditolak, membuktikan rule benar-benar memanggil verifikasi Cloudflare.
5. Kembalikan ke key asli, ulangi langkah 1 dan 2.
6. Pastikan honeypot masih bekerja: isi `confirm_email_address` → tetap ditolak.

## Terkait

- `user-request-kontak-penetapan-cabang-dari-dropdown-city.md` — menyentuh berkas yang sama (`ContactController::store()` dan `kontak.blade.php`). Kalau dua-duanya jadi dikerjakan, satukan biar tidak dua kali bongkar.
- `temuan-keamanan-audit-branch.md` #10 dan #11 — juga di area `contacts`.
