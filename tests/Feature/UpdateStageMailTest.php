<?php

use App\Mail\AcceptedEmail;
use App\Mail\InterviewEmail;
use App\Mail\InterviewLanjutanEmail;
use App\Mail\InterviewOfflineEmail;
use App\Mail\MCUEmail;
use App\Mail\OfferingEmail;
use App\Mail\PsikotesEmail;
use App\Mail\RejectedEmail;
use App\Mail\TesKepribadianEmail;
use App\Models\Admin;
use App\Models\Applicant;
use App\Models\Admin\Lowongan;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Tes pengiriman email saat tahap seleksi diubah
|--------------------------------------------------------------------------
|
| Menembak endpoint admin/applications/update-stage yang sungguhan, lalu
| memastikan email yang terkirim adalah kelas yang benar dan membawa isi
| yang benar.
|
| Tidak ada email yang benar-benar keluar: Mail::fake() menahan semuanya.
| Tidak ada kata sandi yang diketik di mana pun: actingAs() memakai guard
| 'admin' langsung.
|
| Gunanya: waktu nanti jalur Update Massal ikut disatukan, penyimpangan
| apa pun antara jalur satuan dan jalur massal langsung ketahuan di sini.
|
*/

/** Bikin satu lamaran lengkap (user -> applicant -> application -> lowongan). */
function buatLamaran(string $kategori = 'Profesional'): Application
{
    $user = User::factory()->create();
    $applicant = Applicant::create(['user_id' => $user->id, 'status' => 'active']);

    $lowongan = Lowongan::create([
        'kode_lowongan'  => 'UJI-'.uniqid(),
        'judul_lowongan' => 'Lowongan Uji',
        'kategori'       => $kategori,
        'lokasi_kerja'   => 'Jakarta',
        'tanggal_mulai'  => now()->toDateString(),
        'tanggal_akhir'  => now()->addMonth()->toDateString(),
    ]);

    return Application::create([
        'applicant_id' => $applicant->id,
        'lowongan_id'  => $lowongan->id,
        'status'       => 'pending',
    ]);
}

/** Superadmin, supaya penjagaan cabang tidak ikut menghalangi. */
function superadmin(): Admin
{
    return Admin::create([
        'name'     => 'Superadmin Uji',
        'email'    => 'superadmin.uji'.uniqid().'@sosro.test',
        'password' => bcrypt(str()->random(32)),
        'role'     => 'superadmin',
    ]);
}

function pindahTahap(array $isian)
{
    return test()->actingAs(superadmin(), 'admin')
        ->post(route('admin.applications.update-stage'), $isian);
}

beforeEach(fn () => Mail::fake());

// Kolom pertama: isian admin. Kolom kedua: kelas email yang HARUS terkirim.
dataset('tahap yang mengirim email', [
    'psikotes standar' => [
        ['next_status' => 'psikotes', 'psikotes_type' => 'psikotes', 'psikotes_date' => '2026-09-01', 'psikotes_link' => 'https://a', 'psikotes_token' => 'TOKEN-1'],
        PsikotesEmail::class,
    ],
    'psikotes tes kepribadian' => [
        ['next_status' => 'psikotes', 'psikotes_type' => 'tes_kepribadian', 'psikotes_date' => '2026-09-01', 'psikotes_link' => 'https://a', 'psikotes_token' => 'TOKEN-2'],
        TesKepribadianEmail::class,
    ],
    'interview awal' => [
        ['next_status' => 'interview', 'interview_type' => 'initial', 'interview_date' => '2026-09-02', 'interview_link' => 'https://b'],
        InterviewEmail::class,
    ],
    'interview lanjutan' => [
        ['next_status' => 'interview', 'interview_type' => 'lanjutan', 'interview_date' => '2026-09-02', 'interview_link' => 'https://b'],
        InterviewLanjutanEmail::class,
    ],
    'interview offline' => [
        ['next_status' => 'interview', 'interview_type' => 'offline', 'interview_date' => '2026-09-02', 'interview_link' => 'Kantor Pusat'],
        InterviewOfflineEmail::class,
    ],
    'offering' => [
        ['next_status' => 'offering'],
        OfferingEmail::class,
    ],
    'mcu' => [
        ['next_status' => 'mcu', 'mcu_date' => '2026-09-03', 'mcu_location_name' => 'RS Uji', 'mcu_location_address' => 'Jl Uji'],
        MCUEmail::class,
    ],
    'accepted' => [
        ['next_status' => 'accepted', 'join_date' => '2026-10-01', 'work_location' => 'KPW Jawa Barat', 'office_address' => 'Jl Kantor', 'office_type' => 'KPW'],
        AcceptedEmail::class,
    ],
    'rejected' => [
        ['next_status' => 'rejected', 'rejection_reason' => 'Belum sesuai kualifikasi'],
        RejectedEmail::class,
    ],
]);

test('tahap tertentu mengirim kelas email yang benar', function (array $isian, string $kelasEmail) {
    $application = buatLamaran();

    pindahTahap(['application_id' => $application->id] + $isian)
        ->assertRedirect();

    expect($application->fresh()->status)->toBe($isian['next_status']);

    Mail::assertSent($kelasEmail, 1);
    Mail::assertSentCount(1);   // memastikan tidak ada email lain ikut terkirim
})->with('tahap yang mengirim email');

// Kalau tahap ini tiba-tiba mulai mengirim email, itu perubahan yang harus
// disengaja -- bukan efek samping refactor.
test('tahap yang memang tidak mengirim email tetap diam', function (string $tahap) {
    $application = buatLamaran('Management Trainee');

    pindahTahap(['application_id' => $application->id, 'next_status' => $tahap])
        ->assertRedirect();

    expect($application->fresh()->status)->toBe($tahap);
    Mail::assertNothingSent();
})->with(['administration', 'study case', 'panel bod']);

test('isi email psikotes membawa tanggal, tautan, dan token', function () {
    $application = buatLamaran();

    pindahTahap([
        'application_id' => $application->id,
        'next_status'    => 'psikotes',
        'psikotes_type'  => 'psikotes',
        'psikotes_date'  => '2026-09-01',
        'psikotes_link'  => 'https://tes.example',
        'psikotes_token' => 'TOKEN-XYZ',
    ]);

    Mail::assertSent(PsikotesEmail::class, function ($mail) {
        return $mail->data['psikotes_date'] === '2026-09-01'
            && $mail->data['psikotes_link'] === 'https://tes.example'
            && $mail->data['psikotes_token'] === 'TOKEN-XYZ';
    });
});

test('email penolakan membawa alasannya', function () {
    $application = buatLamaran();

    pindahTahap([
        'application_id'   => $application->id,
        'next_status'      => 'rejected',
        'rejection_reason' => 'ALASAN-UJI',
    ]);

    Mail::assertSent(RejectedEmail::class, fn ($mail) => $mail->reason === 'ALASAN-UJI');
    expect($application->fresh()->notes)->toBe('ALASAN-UJI');
});

test('email diterima memilih template sesuai jenis kantor', function () {
    $application = buatLamaran();

    pindahTahap([
        'application_id' => $application->id,
        'next_status'    => 'accepted',
        'join_date'      => '2026-10-01',
        'work_location'  => 'Pabrik Ungaran',
        'office_address' => 'Jl Pabrik',
        'office_type'    => 'KPB',
    ]);

    Mail::assertSent(AcceptedEmail::class, fn ($mail) => $mail->type === 'KPB');
});

// Aturan required_if sekarang dibangkitkan dari config/recruitment.php.
test('kolom wajib per tahap ditolak kalau kosong', function (string $tahap, array $kolomWajib) {
    $application = buatLamaran();

    pindahTahap(['application_id' => $application->id, 'next_status' => $tahap])
        ->assertSessionHasErrors($kolomWajib);

    expect($application->fresh()->status)->toBe('pending');
    Mail::assertNothingSent();
})->with([
    ['psikotes',  ['psikotes_type', 'psikotes_date', 'psikotes_link']],
    ['interview', ['interview_date', 'interview_type', 'interview_link']],
    ['mcu',       ['mcu_date']],
    ['accepted',  ['join_date']],
    ['rejected',  ['rejection_reason']],
]);

test('tahap ngawur ditolak', function () {
    $application = buatLamaran();

    pindahTahap(['application_id' => $application->id, 'next_status' => 'tahap-tidak-ada'])
        ->assertSessionHasErrors('next_status');

    Mail::assertNothingSent();
});

/*
|--------------------------------------------------------------------------
| Jalur Update Massal
|--------------------------------------------------------------------------
|
| Sebelum disatukan, jalur massal punya salinan sendiri untuk memilih kelas
| email -- dan salinan itu sudah menyimpang: tes kepribadian dan interview
| lanjutan TIDAK BISA dikirim massal sama sekali.
|
| Tes di bawah mengunci supaya kedua jalur tidak bisa berbeda lagi.
|
*/

function prosesMassal(string $status, array $barisPerLamaran)
{
    return test()->actingAs(superadmin(), 'admin')
        ->post(route('admin.applicants.bulkProcess'), [
            'status'     => $status,
            'applicants' => $barisPerLamaran,
        ]);
}

test('massal memilih kelas email yang sama dengan jalur satuan', function (string $status, array $isian, string $kelasEmail) {
    $a = buatLamaran();
    $b = buatLamaran();

    prosesMassal($status, [$a->id => $isian, $b->id => $isian])->assertRedirect();

    expect($a->fresh()->status)->toBe($status)
        ->and($b->fresh()->status)->toBe($status);

    Mail::assertSent($kelasEmail, 2);
    Mail::assertSentCount(2);
})->with([
    'psikotes standar'   => ['psikotes',  ['psikotes_type' => 'psikotes',        'psikotes_date' => '2026-09-01', 'psikotes_link' => 'https://a', 'psikotes_token' => 'T1'], PsikotesEmail::class],
    'psikotes kepribadian' => ['psikotes', ['psikotes_type' => 'tes_kepribadian', 'psikotes_date' => '2026-09-01', 'psikotes_link' => 'https://a', 'psikotes_token' => 'T2'], TesKepribadianEmail::class],
    'interview awal'     => ['interview', ['interview_type' => 'initial',  'interview_date' => '2026-09-02', 'interview_link' => 'https://b'], InterviewEmail::class],
    'interview lanjutan' => ['interview', ['interview_type' => 'lanjutan', 'interview_date' => '2026-09-02', 'interview_link' => 'https://b'], InterviewLanjutanEmail::class],
    'interview offline'  => ['interview', ['interview_type' => 'offline',  'interview_date' => '2026-09-02', 'interview_link' => 'Kantor'],    InterviewOfflineEmail::class],
]);

test('massal juga meneruskan isian ke template email', function () {
    $a = buatLamaran();

    prosesMassal('psikotes', [$a->id => [
        'psikotes_type'  => 'psikotes',
        'psikotes_date'  => '2026-09-09',
        'psikotes_link'  => 'https://massal.example',
        'psikotes_token' => 'TOKEN-MASSAL',
    ]]);

    Mail::assertSent(PsikotesEmail::class, function ($mail) {
        return $mail->data['psikotes_link'] === 'https://massal.example'
            && $mail->data['psikotes_token'] === 'TOKEN-MASSAL';
    });
});

// Tahap tanpa layar persiapan (mis. rejected) langsung mengubah status.
test('massal untuk tahap tanpa isian tambahan langsung mengubah status', function () {
    $a = buatLamaran();
    $b = buatLamaran();

    test()->actingAs(superadmin(), 'admin')
        ->post(route('admin.applicants.bulkPrepare'), [
            'status'       => 'administration',
            'selected_ids' => [$a->id, $b->id],
        ])->assertRedirect();

    expect($a->fresh()->status)->toBe('administration')
        ->and($b->fresh()->status)->toBe('administration');

    Mail::assertNothingSent();
});

// Kalau tahapnya punya layar persiapan, bulkPrepare menampilkan halamannya
// (bukan langsung mengubah status).
test('massal untuk psikotes menampilkan layar persiapan dulu', function () {
    $a = buatLamaran();

    test()->actingAs(superadmin(), 'admin')
        ->post(route('admin.applicants.bulkPrepare'), [
            'status'       => 'psikotes',
            'selected_ids' => [$a->id],
        ])->assertOk();

    expect($a->fresh()->status)->toBe('pending');
    Mail::assertNothingSent();
});

// Penjagaan cabang: admin cabang tidak boleh menyentuh lamaran cabang lain,
// termasuk lewat jalur massal.
test('admin cabang tidak bisa memproses lamaran cabang lain secara massal', function () {
    $cabangA = App\Models\Cabang::create(['nama' => 'Cabang A '.uniqid(), 'kelompok' => 'KPW']);
    $cabangB = App\Models\Cabang::create(['nama' => 'Cabang B '.uniqid(), 'kelompok' => 'KPW']);

    $adminCabangA = Admin::create([
        'name' => 'Admin A', 'email' => 'admin.a'.uniqid().'@sosro.test',
        'password' => bcrypt(str()->random(32)), 'role' => 'admin', 'cabang_id' => $cabangA->id,
    ]);

    $milikB = buatLamaran();
    $milikB->lowongan->update(['cabang_id' => $cabangB->id]);

    test()->actingAs($adminCabangA, 'admin')
        ->post(route('admin.applicants.bulkProcess'), [
            'status'     => 'psikotes',
            'applicants' => [$milikB->id => ['psikotes_type' => 'psikotes', 'psikotes_date' => '2026-09-01', 'psikotes_link' => 'https://a']],
        ]);

    expect($milikB->fresh()->status)->toBe('pending');
    Mail::assertNothingSent();
});

/*
|--------------------------------------------------------------------------
| Kesahihan tahap terhadap kategori lowongan
|--------------------------------------------------------------------------
|
| Sebelumnya validasi cuma memeriksa "kunci tahap ini ada di daftar", tanpa
| peduli kategori. Jadi POST langsung dengan tahap milik kategori lain tetap
| lolos, meski opsinya tidak pernah muncul di dropdown.
|
*/

test('tahap milik kategori lain ditolak', function (string $kategori, string $tahapAsing, array $isianWajib = []) {
    $application = buatLamaran($kategori);

    // Kolom wajib tahap itu tetap diisi, supaya yang menolak benar-benar
    // pemeriksaan kategori -- bukan sekadar validasi kolom kosong.
    pindahTahap([
        'application_id' => $application->id,
        'next_status'    => $tahapAsing,
    ] + $isianWajib)->assertSessionHasErrors('next_status');

    expect($application->fresh()->status)->toBe('pending');
    Mail::assertNothingSent();
})->with([
    'simulasi bukan milik Profesional'        => ['Profesional', 'simulasi'],
    'study case bukan milik Profesional'      => ['Profesional', 'study case'],
    'panel bod bukan milik Magang'            => ['Magang', 'panel bod'],
    'mcu bukan milik Magang'                  => ['Magang', 'mcu', ['mcu_date' => '2026-09-03']],
    'simulasi bukan milik Management Trainee' => ['Management Trainee', 'simulasi'],
]);

test('tahap milik kategorinya sendiri tetap diterima', function (string $kategori, string $tahap) {
    $application = buatLamaran($kategori);

    pindahTahap(['application_id' => $application->id, 'next_status' => $tahap])
        ->assertSessionHasNoErrors();

    expect($application->fresh()->status)->toBe($tahap);
})->with([
    ['Profesional', 'administration'],
    ['Magang', 'simulasi'],
    ['Management Trainee', 'panel bod'],
]);

// accepted/rejected berlaku di semua kategori, dari titik mana pun.
test('tahap universal diterima di semua kategori', function (string $kategori) {
    $application = buatLamaran($kategori);

    pindahTahap([
        'application_id'   => $application->id,
        'next_status'      => 'rejected',
        'rejection_reason' => 'Alasan uji',
    ])->assertSessionHasNoErrors();

    expect($application->fresh()->status)->toBe('rejected');
})->with(['Profesional', 'Management Trainee', 'Magang']);
