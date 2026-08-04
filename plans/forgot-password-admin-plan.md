# Forgot Password Admin - Plan

## Context

Fitur "lupa password" saat ini cuma berfungsi untuk pelamar (guard `web`, tabel `users`) — bawaan Laravel Breeze (`routes/auth.php`, `PasswordResetLinkController`, `NewPasswordController`, `resources/views/auth/forgot-password.blade.php` & `reset-password.blade.php`). Admin (guard `admin`, tabel `admins`) tidak punya alur ini sama sekali.

Yang menarik: `resources/views/admin/login.blade.php:40-42` **sudah ada link "Lupa Password?"**, tapi mengarah ke `route('password.request')` — route punya pelamar. Ini bug pre-existing: kalau admin klik link itu sekarang, dia akan masuk ke alur forgot-password milik pelamar (guard/tabel salah), bukan error langsung, tapi hasil akhirnya email admin tidak akan pernah ketemu di tabel `users` sehingga reset gagal diam-diam.

Tujuan: buat alur forgot-password terpisah untuk admin, yang benar-benar menunjuk ke provider `admins` dan mengirim email dengan link yang mengarah ke halaman reset khusus admin.

Keputusan yang sudah diambil bersama user:
- Reuse tabel `password_reset_tokens` yang sudah ada — tidak bikin migrasi/tabel baru.
- Tidak perlu Cloudflare Turnstile di form forgot-password admin (beda dari form login yang pakai Turnstile) — konsisten dengan forgot-password pelamar yang juga tidak pakai captcha.

## Pendekatan

### 1. Broker baru di `config/auth.php`
Tambah entri `admins` di `'passwords' => [...]` (setelah entri `users`, baris ~54-60), reuse tabel yang sama:
```php
'admins' => [
    'provider' => 'admins',
    'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
    'expire' => 60,
    'throttle' => 60,
],
```

### 2. Override `sendPasswordResetNotification()` di `App\Models\Admin`
Supaya link reset di email admin menunjuk ke route admin, bukan `password.reset` milik pelamar. Pendekatan ini dipilih dibanding `ResetPassword::createUrlUsing()` global (yang static dan dipakai bersama oleh `User` & `Admin`, rawan salah kalau lupa branching) — override langsung di model jauh lebih eksplisit dan aman.

Buat class notification baru `app/Notifications/AdminResetPasswordNotification.php` yang extend `Illuminate\Auth\Notifications\ResetPassword`, override method `resetUrl($notifiable)` supaya build URL pakai `route('admin.password.reset', [...])` bukan `route('password.reset', [...])`.

Lalu di `app/Models/Admin.php`, tambah method:
```php
use App\Notifications\AdminResetPasswordNotification;

public function sendPasswordResetNotification($token)
{
    $this->notify(new AdminResetPasswordNotification($token));
}
```

### 3. Controller baru — `app/Http/Controllers/Admin/Auth/AdminPasswordResetLinkController.php` dan `AdminNewPasswordController.php`
Mengikuti pola persis `PasswordResetLinkController`/`NewPasswordController` yang ada (`app/Http/Controllers/Auth/`), tapi:
- Namespace `App\Http\Controllers\Admin\Auth` (konsisten dengan `AdminAuthController`)
- Panggil `Password::broker('admins')->sendResetLink(...)` dan `Password::broker('admins')->reset(...)` (bukan default broker)
- Closure di `reset()` di-type-hint `App\Models\Admin` (bukan `App\Models\User`)
- Redirect sukses ke `route('admin.login')` (bukan `route('login')`)
- View yang direturn: `admin.forgot-password` dan `admin.reset-password`

### 4. View baru — `resources/views/admin/forgot-password.blade.php` dan `resources/views/admin/reset-password.blade.php`
Pakai `<x-guest-layout>` (sama seperti `admin/login.blade.php`, bukan `<x-app-layout>` yang dipakai versi pelamar), dan ikuti visual style `admin/login.blade.php`: card putih rounded, badge "Administrator Portal", input custom-styled, tombol CTA kuning (`bg-[#ffbf34]`), logo SGS. Form forgot-password post ke route baru `admin.password.email`; form reset-password post ke `admin.password.store` dengan hidden field `token` dan `email`.

### 5. Route baru di `routes/web.php`
Ditambahkan di sekitar baris 108-110 (sebaris dengan `admin.login`/`admin.logout`, **di luar** group `auth:admin` karena diakses saat belum login), dibungkus `middleware('guest:admin')`:
```php
Route::middleware('guest:admin')->group(function () {
    Route::get('admin/forgot-password', [AdminPasswordResetLinkController::class, 'create'])
        ->name('admin.password.request');
    Route::post('admin/forgot-password', [AdminPasswordResetLinkController::class, 'store'])
        ->name('admin.password.email');
    Route::get('admin/reset-password/{token}', [AdminNewPasswordController::class, 'create'])
        ->name('admin.password.reset');
    Route::post('admin/reset-password', [AdminNewPasswordController::class, 'store'])
        ->name('admin.password.store');
});
```

### 6. Perbaiki link yang sudah ada di `admin/login.blade.php:40`
Ganti `route('password.request')` → `route('admin.password.request')`. Ini memperbaiki bug pre-existing sekaligus menyambungkan ke alur baru.

## File yang tersentuh
- `config/auth.php` (tambah broker)
- `app/Models/Admin.php` (override method notifikasi)
- `app/Notifications/AdminResetPasswordNotification.php` (baru)
- `app/Http/Controllers/Admin/Auth/AdminPasswordResetLinkController.php` (baru)
- `app/Http/Controllers/Admin/Auth/AdminNewPasswordController.php` (baru)
- `resources/views/admin/forgot-password.blade.php` (baru)
- `resources/views/admin/reset-password.blade.php` (baru)
- `routes/web.php` (tambah 4 route)
- `resources/views/admin/login.blade.php` (perbaiki 1 link)

## Verifikasi
- Login sebagai admin/superadmin gagal sengaja dulu → dari halaman login klik "Lupa Password?" → pastikan mengarah ke `/admin/forgot-password` (bukan halaman pelamar).
- Submit email admin yang valid → cek email masuk (pakai SMTP yang sudah disetup sebelumnya) → pastikan link di email mengarah ke `/admin/reset-password/{token}?email=...` bukan `/reset-password/...`.
- Klik link, isi password baru, submit → redirect ke `admin.login` dengan pesan sukses.
- Login pakai password baru → berhasil masuk dashboard admin.
- Pastikan alur pelamar (`/forgot-password`) tetap berfungsi normal seperti sebelumnya (tidak ada regresi akibat broker baru).
