<?php

use App\Models\RecruitmentStage;

/*
|--------------------------------------------------------------------------
| Tes karakterisasi tahapan seleksi
|--------------------------------------------------------------------------
|
| Ini BUKAN tes yang menilai apakah tahapannya sudah benar secara bisnis.
| Ini memotret keluaran keenam accessor RecruitmentStage APA ADANYA per
| 23 Agustus 2026, supaya perubahan sumber data berikutnya (DB -> config)
| bisa DIBUKTIKAN tidak mengubah perilaku, bukan sekadar dipercaya.
|
| Kalau tes ini merah setelah refactor, artinya ada yang bergeser --
| perbaiki refactornya, jangan perbarui angkanya di sini. Nilai di bawah
| baru boleh diubah kalau tahapannya memang sengaja diubah.
|
*/

beforeEach(function () {
    // Tidak perlu seeding: sumber datanya config/recruitment.php, bukan database.

    // RecruitmentStage::$cache statis dan hidup sepanjang proses PHP, jadi
    // antar-tes bisa nyangkut dan bikin tes lolos padahal sumbernya tidak
    // pernah dibaca ulang. Dikosongkan paksa lewat refleksi.
    $cache = new ReflectionProperty(RecruitmentStage::class, 'cache');
    $cache->setAccessible(true);
    $cache->setValue(null, null);
});

// Urutan PENTING di sini -- ini yang menentukan susunan optgroup di dropdown
// admin (resources/views/admin/applicants/index.blade.php).
test('pipelines() mengembalikan 3 kategori dengan urutan tahap yang tetap', function () {
    expect(RecruitmentStage::pipelines())->toBe([
        'Profesional' => [
            'administration', 'psikotes', 'interview', 'offering', 'mcu',
        ],
        'Management Trainee' => [
            'administration', 'psikotes', 'interview', 'study case', 'panel bod', 'offering', 'mcu',
        ],
        'Magang' => [
            'administration', 'psikotes', 'interview', 'simulasi', 'offering',
        ],
    ]);
});

test('labels() memetakan 11 kunci tahap ke label sisi admin', function () {
    expect(RecruitmentStage::labels())->toEqual([
        'pending'        => 'Pending',
        'administration' => 'Lolos Administrasi',
        'psikotes'       => 'Psikotes',
        'interview'      => 'Interview',
        'study case'     => 'Study Case',
        'panel bod'      => 'Panel BoD',
        'simulasi'       => 'Simulasi',
        'offering'       => 'Offering Letter',
        'mcu'            => 'MCU',
        'accepted'       => 'Accepted',
        'rejected'       => 'Rejected',
    ]);
});

// Hanya tahap yang punya override yang masuk -- sisanya jatuh ke labels().
test('applicantLabels() hanya berisi 5 tahap yang namanya beda di sisi pelamar', function () {
    expect(RecruitmentStage::applicantLabels())->toEqual([
        'pending'  => 'Terkirim',
        'simulasi' => 'Simulasi Field',
        'mcu'      => 'Medical Check Up',
        'accepted' => 'Diterima (Hired)',
        'rejected' => 'Ditolak',
    ]);
});

test('colors() memetakan 11 kunci tahap ke kelas Tailwind badge', function () {
    expect(RecruitmentStage::colors())->toEqual([
        'pending'        => 'bg-yellow-50 text-yellow-600 border-yellow-100',
        'administration' => 'bg-purple-50 text-purple-600 border-purple-100',
        'psikotes'       => 'bg-blue-50 text-blue-600 border-blue-100',
        'interview'      => 'bg-cyan-50 text-cyan-600 border-cyan-100',
        'study case'     => 'bg-indigo-50 text-indigo-600 border-indigo-100',
        'panel bod'      => 'bg-violet-50 text-violet-600 border-violet-100',
        'simulasi'       => 'bg-orange-50 text-orange-600 border-orange-100',
        'offering'       => 'bg-pink-50 text-pink-600 border-pink-100',
        'mcu'            => 'bg-teal-50 text-teal-600 border-teal-100',
        'accepted'       => 'bg-green-50 text-green-600 border-green-100',
        'rejected'       => 'bg-red-50 text-red-600 border-red-100',
    ]);
});

// Dipakai sebagai whitelist Rule::in() di ApplicantController::updateStage().
test('allKeys() berisi 11 kunci tahap', function () {
    expect(RecruitmentStage::allKeys())->toEqualCanonicalizing([
        'pending', 'administration', 'psikotes', 'interview', 'study case',
        'panel bod', 'simulasi', 'offering', 'mcu', 'accepted', 'rejected',
    ]);
});

// Urutan PENTING -- menentukan susunan opsi di dropdown Update Massal.
test('bulkUpdateStages() berisi 4 tahap yang boleh diubah massal', function () {
    expect(RecruitmentStage::bulkUpdateStages())->toBe([
        'administration', 'psikotes', 'interview', 'rejected',
    ]);
});

// Catatan: method ini NOL pemanggil di seluruh app/, resources/, dan database/.
// Dipotret di sini supaya kalau nanti dihidupkan, nilai awalnya terekam.
test('universalStages() berisi 3 tahap di luar urutan pipeline', function () {
    expect(RecruitmentStage::universalStages())->toEqualCanonicalizing([
        'pending', 'accepted', 'rejected',
    ]);
});
