# Kunci akses /admin/users khusus role superadmin

## Kondisi sekarang

`UserController::index()` (`app/Http/Controllers/Admin/UserController.php:14-21`) memblokir akses non-superadmin dengan `abort(403, ...)` **di dalam controller** — artinya route tetap dieksekusi dulu, baru dibatalkan. Link "Pengguna" di sidebar (`resources/views/admin/layout.blade.php:367-373`) tampil untuk semua role admin tanpa pengecualian.

Bug tambahan yang ditemukan: baris 17 controller memakai `Auth::user()` (guard default `web`), padahal login admin cuma melakukan `Auth::guard('admin')->attempt()` (`AdminAuthController.php:36`). Default guard `web` tidak pernah terautentikasi di halaman admin, jadi `Auth::user()` selalu `null` di situ — kemungkinan besar `index()` **selalu abort untuk siapa pun**, termasuk superadmin, saat ini.

Pola yang sama (`Auth::user()->role !== 'superadmin'` tanpa `guard('admin')`) juga dipakai di `LaporanController`, `ApplicantController`, `LowonganController`, `ContactController`, dan tampilan nama/foto admin (`admin/layout.blade.php:414-415`) — kemungkinan besar juga bermasalah, tapi di luar cakupan perbaikan ini.

## Yang akan dilakukan

**1. Middleware baru — `app/Http/Middleware/EnsureIsSuperAdmin.php`**
File baru: cek user lewat guard `admin`, kalau rolenya bukan `superadmin` → `abort(403)` sebelum request sampai ke controller.
*Efek:* pengecekan role pindah dari "di dalam controller" ke "sebelum route dijalankan sama sekali". Role `admin` yang akses `/admin/users` diblokir di gerbang paling depan.

**2. Daftarkan middleware di `bootstrap/app.php`**
Tambah satu alias supaya middleware bisa dipanggil dengan nama pendek `'superadmin'` di route.
*Efek:* tidak mengubah behavior apa pun sendirian — ini cuma pendaftaran nama.

**3. Tempel middleware ke route `GET /admin/users` di `routes/web.php`**
Tambah `->middleware('superadmin')` khusus di route `users.index` (baris 138-139). `users.store` dan `users.destroy` **tidak** disentuh.
*Efek:* hanya halaman index yang diblokir. Kalau role admin tahu URL POST/DELETE-nya (jarang lewat UI biasa, tapi bisa lewat curl/Postman), itu tetap bisa jalan seperti sekarang — konsekuensi dari keputusan scope, bukan bug baru.

**4. Hapus pengecekan manual di `UserController::index()`**
Baris `if (!$admin || $admin->role !== 'superadmin') abort(403, ...)` dihapus karena sudah digantikan middleware.
*Efek:* tidak ada cek role dobel. Sekaligus memperbaiki bug guard salah, sehingga superadmin bisa benar-benar membuka halaman ini lagi.

**5. Sembunyikan link "Pengguna" di sidebar — `resources/views/admin/layout.blade.php`**
Bungkus tag `<a>` link Pengguna dengan:
```blade
@if(Auth::guard('admin')->user()?->role === 'superadmin')
    ...link Pengguna...
@endif
```
*Efek:* untuk role `admin` biasa, HTML link ini tidak pernah dikirim ke browser sama sekali (bukan cuma disembunyikan lewat CSS). Untuk superadmin, link tetap muncul normal.

## Yang sengaja TIDAK diubah

`store()` dan `destroy()` di `UserController` tetap tanpa role-check tambahan — jadi kalau ada yang tahu endpoint-nya langsung, admin biasa masih bisa create/delete akun via request langsung. Ini risiko yang diketahui, bukan sesuatu yang diperbaiki di scope ini.

File yang tersentuh: 1 file middleware baru, `bootstrap/app.php`, `routes/web.php`, `UserController.php`, `admin/layout.blade.php`. Tidak ada perubahan skema database/migrasi.

## Verifikasi

- `php artisan route:list --name=admin.users.index` — pastikan middleware `superadmin` terpasang.
- Login sebagai role `admin`: link "Pengguna" tidak muncul di sidebar, akses langsung ke `/admin/users` menghasilkan 403.
- Login sebagai role `superadmin`: link tetap muncul, halaman `/admin/users` tetap bisa dibuka & menampilkan data seperti sebelumnya.
- Tes lewat browser (dev server) untuk kedua role, bukan cuma baca kode.
