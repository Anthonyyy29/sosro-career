# Sisa Konsolidasi Tahap Seleksi: Butir F (Buang Tabel DB)

**Urgensi: RENDAH.** Tidak ada yang rusak kalau dibiarkan. Dua tabel itu sekarang
cuma menganggur — masih ada, masih diisi seeder, tapi **tidak pernah dibaca aplikasi**.

**Status: sengaja ditahan.** Butir A–E sudah selesai dan ter-commit (`17cf25b`
sampai `2dfefdb`). Butir F tidak dikerjakan karena menghapus tabel **tidak bisa
dibatalkan di produksi**, dan tidak pantas dijalankan tanpa pengawasan.

## Yang sudah selesai

| | Yang dibereskan | Commit |
|---|---|---|
| — | Tes karakterisasi sebagai jaring pengaman | `17cf25b` |
| — | Sumber data pindah dari DB ke `config/recruitment.php` | `a586aec` |
| A | Dropdown dirakit dari satu sumber, `<option>` hardcode dibuang | `8b3cd39` |
| B | Email & kolom wajib pindah ke config, rantai `elseif` runtuh | `4fecc81` |
| — | 21 tes pengiriman email lewat endpoint sungguhan | `5de86bf` |
| C | Jalur Update Massal disatukan + penjagaan cabang (temuan #5) | `1488aa6` |
| D | Isian per tahap jadi berkas sendiri | `2dfefdb` |
| E | Validasi mengecek kesahihan tahap terhadap kategori | `2dfefdb` |

## Yang tersisa (butir F)

1. **Hapus dua tabel** lewat migrasi baru: `recruitment_stages` dan
   `recruitment_stage_pipeline`.
2. **Hapus `database/seeders/RecruitmentStageSeeder.php`** dan pendaftarannya di
   `DatabaseSeeder`.
3. **Hapus `app/Models/RecruitmentStagePipeline.php`.**
4. **Ubah `app/Models/RecruitmentStage.php`** supaya tidak lagi `extends Model` —
   isinya sekarang cuma method statis pembaca config. Buang juga `$fillable`,
   `$casts`, dan relasi `pipelineEntries()`.
5. **Sekalian**: migrasi kecil `->default('pending')` untuk `applications.status`.
   Sekarang kolomnya `varchar(255)` tanpa default, jadi jalur pembuatan baru yang
   lupa mengisi akan menghasilkan `NULL` yang tidak cocok filter mana pun.

## Kenapa ditahan

Nomor 1 permanen begitu jalan di produksi. Nomor 4 menyentuh berkas yang dipakai
15 titik di 4 berkas — aman menurut tes, tapi pantas dikerjakan sambil diperhatikan.

Nomor 2–5 sebenarnya rendah risiko. Kalau mau dicicil: kerjakan 2–4 dulu, tahan
nomor 1 sampai benar-benar yakin.

## Jaring pengaman yang sudah tersedia

- `tests/Feature/RecruitmentStageTest.php` — 7 tes memotret keenam pembaca config.
- `tests/Feature/UpdateStageMailTest.php` — 41 tes: kelas email per tahap (satuan
  dan massal), isi email, kolom wajib, penjagaan cabang, kesahihan kategori.

Keduanya **sudah dibuktikan bisa gagal**, bukan sekadar selalu hijau.

## Verifikasi setelah butir F

1. `./vendor/bin/sail artisan test` — tetap 2 gagal (Turnstile, baseline lama),
   tidak bertambah.
2. `./vendor/bin/sail artisan migrate:fresh --seed` di DB testing, lalu buka
   `/admin/applicants` — dropdown dan badge masih benar.
3. `./vendor/bin/sail artisan config:cache` berhasil, halaman masih normal.
4. Pastikan tidak ada sisa rujukan: `grep -rn "recruitment_stage" app/ database/`.

## Catatan

Blok `practical_test` di `admin/Applicants/index.blade.php` sengaja **tidak
disentuh** atas permintaan user, walau sudah berupa komentar mati.
