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
