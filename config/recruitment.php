<?php

/*
|--------------------------------------------------------------------------
| Tahapan Seleksi Rekrutmen
|--------------------------------------------------------------------------
|
| Berkas ini menentukan tahapan seleksi: namanya apa, warnanya apa, dan
| urutannya bagaimana untuk tiap kategori lowongan.
|
| Kalau mau menambah, mengubah, atau menghapus tahap seleksi, di sinilah
| tempatnya. Tidak perlu menyentuh database.
|
| Isinya dibaca lewat App\Models\RecruitmentStage. Dulu data ini disimpan di
| dua tabel database (recruitment_stages dan recruitment_stage_pipeline).
| Tabelnya masih ada, tapi sudah tidak dibaca lagi oleh aplikasi.
|
| ATURAN: isi berkas ini harus data biasa saja -- teks, angka, true/false,
| dan array. JANGAN menaruh fungsi di sini, yaitu yang ditulis dengan
| `function () { ... }` atau `fn () => ...`.
|
| Alasannya: di produksi biasanya dijalankan perintah `php artisan
| config:cache`, yang menyalin isi semua berkas config jadi satu berkas
| supaya server lebih cepat. Teks dan angka bisa disalin, fungsi tidak bisa.
| Kalau ada fungsi di sini, perintah itu gagal.
|
*/

return [

    /*
    | Daftar semua tahap seleksi.
    |
    | Tulisan di sebelah kiri (misalnya 'psikotes') itu KODE tahapnya. Kode ini
    | yang benar-benar tersimpan di kolom `status` tabel `applications`, dan
    | dipakai juga di HTML serta JavaScript halaman admin.
    |
    | Karena itu, kalau menambah tahap baru: pilih kodenya baik-baik dan
    | JANGAN pakai spasi. Mengubah kode yang sudah dipakai berarti harus
    | memperbaiki data lamaran yang sudah terlanjur memakainya.
    |
    | Arti tiap baris:
    |
    |   label           nama tahap yang dilihat ADMIN
    |   applicant_label nama tahap yang dilihat PELAMAR. Diisi hanya kalau
    |                   memang beda; kalau null, pelamar melihat `label`
    |   color           warna badge status (kelas Tailwind)
    |   universal       true = tahap ini di luar urutan, bisa dipilih kapan
    |                   saja. Untuk sekarang belum dipakai kode manapun
    |   bulk            true = tahap ini boleh dipilih di fitur Update Massal
    |                   pada halaman daftar pelamar
    */

    'stages' => [

        'pending' => [
            'label'           => 'Pending',
            'applicant_label' => 'Terkirim',
            'color'           => 'bg-yellow-50 text-yellow-600 border-yellow-100',
            'universal'       => true,
            'bulk'            => false,
        ],

        'administration' => [
            'label'           => 'Lolos Administrasi',
            'applicant_label' => null,
            'color'           => 'bg-purple-50 text-purple-600 border-purple-100',
            'universal'       => false,
            'bulk'            => true,
        ],

        'psikotes' => [
            'label'           => 'Psikotes',
            'applicant_label' => null,
            'color'           => 'bg-blue-50 text-blue-600 border-blue-100',
            'universal'       => false,
            'bulk'            => true,
        ],

        'interview' => [
            'label'           => 'Interview',
            'applicant_label' => null,
            'color'           => 'bg-cyan-50 text-cyan-600 border-cyan-100',
            'universal'       => false,
            'bulk'            => true,
        ],

        'study case' => [
            'label'           => 'Study Case',
            'applicant_label' => null,
            'color'           => 'bg-indigo-50 text-indigo-600 border-indigo-100',
            'universal'       => false,
            'bulk'            => false,
        ],

        'panel bod' => [
            'label'           => 'Panel BoD',
            'applicant_label' => null,
            'color'           => 'bg-violet-50 text-violet-600 border-violet-100',
            'universal'       => false,
            'bulk'            => false,
        ],

        'simulasi' => [
            'label'           => 'Simulasi',
            'applicant_label' => 'Simulasi Field',
            'color'           => 'bg-orange-50 text-orange-600 border-orange-100',
            'universal'       => false,
            'bulk'            => false,
        ],

        'offering' => [
            'label'           => 'Offering Letter',
            'applicant_label' => null,
            'color'           => 'bg-pink-50 text-pink-600 border-pink-100',
            'universal'       => false,
            'bulk'            => false,
        ],

        'mcu' => [
            'label'           => 'MCU',
            'applicant_label' => 'Medical Check Up',
            'color'           => 'bg-teal-50 text-teal-600 border-teal-100',
            'universal'       => false,
            'bulk'            => false,
        ],

        'accepted' => [
            'label'           => 'Accepted',
            'applicant_label' => 'Diterima (Hired)',
            'color'           => 'bg-green-50 text-green-600 border-green-100',
            'universal'       => true,
            'bulk'            => false,
        ],

        'rejected' => [
            'label'           => 'Rejected',
            'applicant_label' => 'Ditolak',
            'color'           => 'bg-red-50 text-red-600 border-red-100',
            'universal'       => true,
            'bulk'            => true,
        ],

    ],

    /*
    | Urutan tahap untuk tiap kategori lowongan.
    |
    | Nama kategori di sebelah kiri harus sama persis dengan isi kolom
    | `kategori` di tabel `lowongan`.
    |
    | Urutannya ditentukan oleh posisi dalam array, jadi mau menyisipkan tahap
    | baru di tengah tinggal diketik di posisinya. (Waktu masih di database,
    | ini merepotkan: tiap tahap punya nomor urut, dan menyisipkan di tengah
    | bikin nomornya bentrok sampai proses seeding-nya gagal.)
    |
    | 'accepted' dan 'rejected' sengaja TIDAK ditulis di sini, karena keduanya
    | bisa dipilih kapan saja -- pelamar bisa ditolak sejak tahap pertama,
    | tidak harus menunggu sampai tahap terakhir. Jadi keduanya tidak punya
    | posisi dalam urutan.
    |
    | Untuk sekarang keduanya masih ditulis manual di halaman admin
    | (resources/views/admin/applicants/index.blade.php), persis seperti
    | sebelum perubahan ini. Merapikan itu urusan langkah berikutnya.
    */

    'pipelines' => [

        'Profesional' => [
            'administration', 'psikotes', 'interview', 'offering', 'mcu',
        ],

        'Management Trainee' => [
            'administration', 'psikotes', 'interview', 'study case', 'panel bod', 'offering', 'mcu',
        ],

        'Magang' => [
            'administration', 'psikotes', 'interview', 'simulasi', 'offering',
        ],

    ],

];
