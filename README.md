# Sosro Career

Portal rekrutmen: daftar lowongan publik, portal pelamar (biodata & lacak lamaran),
dan panel admin (pipeline seleksi, lowongan, laporan). Laravel 12 + Blade + Alpine.js
+ Tailwind, dijalankan lewat Laravel Sail (Docker).

## Menyiapkan di komputer baru

**Yang perlu ada dulu:** Docker Desktop (jalan), PHP + Composer, dan Node.js.

```bash
git clone <url-repo>
cd sosro.career

composer install
npm install
```

### Berkas .env

Berkas ini **tidak ikut di repo** karena berisi kredensial. Mintalah salinannya ke
pemilik repo, taruh di akar proyek dengan nama `.env`.

Tiga hal yang biasanya perlu disesuaikan di komputermu sendiri:

| Isian | Keterangan |
|---|---|
| `APP_KEY` | kosongkan, lalu hasilkan sendiri (langkah di bawah) |
| `MAIL_*` | kalau tidak punya akun pengirim, set `MAIL_MAILER=log` — email tidak benar-benar terkirim, cuma dicatat ke `storage/logs/laravel.log` |
| `TURNSTILE_*` | captcha. Untuk lokal pakai dummy key resmi Cloudflare yang selalu lolos: site `1x00000000000000000000AA`, secret `1x0000000000000000000000000000000AA` |

### Menjalankan

```bash
./vendor/bin/sail up -d                      # nyalakan container
./vendor/bin/sail artisan key:generate       # isi APP_KEY
./vendor/bin/sail artisan migrate --seed     # buat tabel + data awal
npm run dev                                  # aset Vite
```

Buka `http://localhost`. phpMyAdmin ada di `http://localhost:8080`.

Akun awal dibuat oleh seeder — lihat `database/seeders/AdminUserSeeder.php`.

### Yang sering bikin tersandung

**`php artisan` langsung gagal terhubung ke database.** `DB_HOST=mysql` itu nama
container, hanya dikenali dari dalam jaringan Sail. Selalu pakai
`./vendor/bin/sail artisan ...`, bukan `php artisan ...`.

**Dua tes selalu gagal.** `AuthenticationTest` dan `RegistrationTest` — tes bawaan
Breeze yang tidak mengirim token Turnstile, padahal field itu wajib. Ini kondisi
lama, bukan kerusakan. Kalau `sail artisan test` menunjukkan tepat dua kegagalan
itu, berarti tidak ada yang rusak.

**Tahapan seleksi tidak ada di database.** Dulu memang di tabel, sekarang di
`config/recruitment.php`. Mau menambah atau mengubah tahap, sunting berkas itu.

## Perintah sehari-hari

```bash
./vendor/bin/sail artisan test                  # seluruh tes
./vendor/bin/sail artisan test --filter=NamaTes # satu tes
./vendor/bin/pint                               # rapikan gaya kode
npm run build                                   # bangun aset produksi
```

## Dokumentasi lain

- `CLAUDE.md` — penjelasan arsitektur: dua guard autentikasi, penyaringan per
  cabang, pipeline seleksi, model data pelamar
- `plans/` — catatan rencana & temuan, dikelompokkan per status. Lihat
  `plans/README.md` untuk konvensinya
- `plans/db_sosro_normalized_table_list.md` — daftar tabel, disinkronkan dari DB

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
