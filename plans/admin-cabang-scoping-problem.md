# Problem: Admin bisa CRUD lowongan cabang lain

## Masalah

- `LowonganController::store()` — admin bisa pilih `penempatan_cabang` bebas di dropdown, tidak divalidasi harus sama dengan cabangnya sendiri.
- `LowonganController::update()` & `destroy()` — **tidak ada pengecekan kepemilikan sama sekali**. Admin mana pun bisa edit/hapus lowongan siapa pun via request langsung.
- `LowonganController::index()` — sudah filter `created_by = Auth::id()`, tapi ini berdasarkan **akun pembuat**, bukan **cabang**. Jadi 2 admin di cabang yang sama tidak saling lihat lowongan satu sama lain.
- Akar masalah: tabel `admins` tidak punya kolom `cabang`. Tidak ada relasi apa pun antara admin ↔ cabang ↔ lowongan — semua cuma string bebas (`penempatan_cabang`) yang tidak divalidasi ke siapa pun.

## Status: BLOCKED

Nunggu konfirmasi tim deployment: **apakah aman menambah kolom baru ke tabel `admins` di database production sekarang?**

## Dua opsi solusi (pilih setelah dapat jawaban di atas)

**A. Tambah kolom `cabang` di tabel `admins`** (kalau migrasi DB aman)
- Kolom nullable, migration ringan (`ADD COLUMN`), tidak ganggu data lama
- Bisa diatur langsung dari UI admin (superadmin assign cabang ke akun admin kapan saja)
- Scoping di `LowonganController` (store/update/destroy/index) berdasarkan `admin.cabang === lowongan.penempatan_cabang`

**B. Pakai config/array PHP, tanpa migration** (kalau tim deployment keberatan)
- Mapping `email admin → cabang` disimpan di file PHP (`app/Support/AdminCabang.php`), bukan kolom DB
- Nol risiko migrasi
- Trade-off: assign/reassign cabang admin baru butuh developer edit file + deploy, tidak bisa self-service dari UI

## Catatan tambahan (belum diputuskan, bukan prioritas sekarang)

Daftar KPW/Pabrik/Lainnya juga masih hardcode & dobel di 3 file blade (`admin/lowongan/index.blade.php` x2, `pages/kontak.blade.php`). Bisa dirapikan jadi 1 class/config (`Cabang::all()`, dst) — opsional, boleh dikerjakan terpisah dari fix di atas, kapan saja.

## Next step

Tunggu jawaban dari tim deployment → pilih opsi A atau B → baru masuk plan mode untuk detail implementasi.
