<?php

use App\Mail\KonfirmasiUserEmail;
use App\Models\Admin;
use App\Models\Admin\Lowongan;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\Cabang;
use App\Models\User;
use App\Models\UserConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| Tahap "Konfirmasi User"
|--------------------------------------------------------------------------
|
| Admin menyodorkan beberapa kandidat dari satu lowongan ke user lewat satu
| tautan; user memilih salah satu tanpa perlu akun.
|
| Nama fungsi pembantu di berkas ini sengaja dibedakan dari yang ada di
| UpdateStageMailTest.php -- fungsi di berkas tes Pest bersifat global, jadi
| nama yang sama akan bentrok.
|
*/

function lowonganUji(string $kategori = 'Profesional', ?int $cabangId = null): Lowongan
{
    return Lowongan::create([
        'kode_lowongan' => 'KU-'.uniqid(),
        'judul_lowongan' => 'Lowongan Konfirmasi',
        'kategori' => $kategori,
        'lokasi_kerja' => 'Jakarta',
        'cabang_id' => $cabangId,
        'tanggal_mulai' => now()->toDateString(),
        'tanggal_akhir' => now()->addMonth()->toDateString(),
    ]);
}

function lamaranPada(Lowongan $lowongan, string $status = 'interview'): Application
{
    $user = User::factory()->create();
    $applicant = Applicant::create(['user_id' => $user->id, 'status' => 'active']);

    return Application::create([
        'applicant_id' => $applicant->id,
        'lowongan_id' => $lowongan->id,
        'status' => $status,
    ]);
}

function superadminKonfirmasi(): Admin
{
    return Admin::create([
        'name' => 'Superadmin Konfirmasi',
        'email' => 'sa.konf'.uniqid().'@sosro.test',
        'password' => bcrypt(str()->random(32)),
        'role' => 'superadmin',
    ]);
}

function kirimKeUser(array $catatan, string $email = 'user@perusahaan.test')
{
    return test()->actingAs(superadminKonfirmasi(), 'admin')
        ->post(route('admin.user-confirmations.store'), [
            'email_user' => $email,
            'catatan' => $catatan,
        ]);
}

beforeEach(fn () => Mail::fake());

// ---------------------------------------------------------------- sisi admin

test('admin mengirim beberapa kandidat ke user', function () {
    $lowongan = lowonganUji();
    $a = lamaranPada($lowongan);
    $b = lamaranPada($lowongan);

    kirimKeUser([$a->id => 'Komunikasi bagus', $b->id => 'Pengalaman relevan'])
        ->assertRedirect(route('admin.applicants'));

    $konfirmasi = UserConfirmation::latest('id')->first();

    expect($konfirmasi)->not->toBeNull()
        ->and($konfirmasi->email_user)->toBe('user@perusahaan.test')
        ->and($konfirmasi->status)->toBe('menunggu')
        ->and($konfirmasi->selected_application_id)->toBeNull()
        ->and($konfirmasi->items)->toHaveCount(2)
        ->and($konfirmasi->lowongan_id)->toBe($lowongan->id);

    // Status kedua lamaran pindah ke tahap baru
    expect($a->fresh()->status)->toBe('konfirmasi user')
        ->and($b->fresh()->status)->toBe('konfirmasi user');

    // SATU email ke user, bukan satu per pelamar
    Mail::assertSent(KonfirmasiUserEmail::class, 1);
    Mail::assertSentCount(1);
    Mail::assertSent(KonfirmasiUserEmail::class, fn ($mail) => $mail->hasTo('user@perusahaan.test'));
});

test('kandidat dari lowongan berbeda ditolak', function () {
    $a = lamaranPada(lowonganUji());
    $b = lamaranPada(lowonganUji());

    kirimKeUser([$a->id => 'Catatan A', $b->id => 'Catatan B'])
        ->assertSessionHasErrors('catatan');

    expect(UserConfirmation::count())->toBe(0)
        ->and($a->fresh()->status)->toBe('interview');
    Mail::assertNothingSent();
});

test('catatan wajib diisi untuk tiap kandidat', function () {
    $lowongan = lowonganUji();
    $a = lamaranPada($lowongan);

    kirimKeUser([$a->id => ''])->assertSessionHasErrors('catatan.'.$a->id);

    expect(UserConfirmation::count())->toBe(0);
    Mail::assertNothingSent();
});

test('admin cabang tidak bisa menyodorkan kandidat cabang lain', function () {
    $cabangA = Cabang::create(['nama' => 'Cab A '.uniqid(), 'kelompok' => 'KPW']);
    $cabangB = Cabang::create(['nama' => 'Cab B '.uniqid(), 'kelompok' => 'KPW']);

    $adminA = Admin::create([
        'name' => 'Admin A', 'email' => 'a'.uniqid().'@sosro.test',
        'password' => bcrypt(str()->random(32)), 'role' => 'admin', 'cabang_id' => $cabangA->id,
    ]);

    $milikB = lamaranPada(lowonganUji('Profesional', $cabangB->id));

    $this->actingAs($adminA, 'admin')->post(route('admin.user-confirmations.store'), [
        'email_user' => 'user@perusahaan.test',
        'catatan' => [$milikB->id => 'Catatan'],
    ]);

    expect(UserConfirmation::count())->toBe(0)
        ->and($milikB->fresh()->status)->toBe('interview');
    Mail::assertNothingSent();
});

// Tahap ini ber-'bulk_only', jadi tidak sah lewat modal satu pelamar.
test('tahap konfirmasi user ditolak lewat updateStage', function () {
    $application = lamaranPada(lowonganUji());

    $this->actingAs(superadminKonfirmasi(), 'admin')
        ->post(route('admin.applications.update-stage'), [
            'application_id' => $application->id,
            'next_status' => 'konfirmasi user',
        ])->assertSessionHasErrors('next_status');

    expect($application->fresh()->status)->toBe('interview');
});

// ---------------------------------------------------------------- halaman user

function batchUji(int $jumlah = 2): UserConfirmation
{
    $lowongan = lowonganUji();
    $catatan = [];
    foreach (range(1, $jumlah) as $i) {
        $catatan[lamaranPada($lowongan)->id] = 'Catatan kandidat '.$i;
    }
    kirimKeUser($catatan);

    return UserConfirmation::latest('id')->first();
}

function tautanLihat(UserConfirmation $k): string
{
    return URL::temporarySignedRoute('konfirmasi-user.show', $k->expires_at, ['konfirmasi' => $k->id]);
}

function tautanPilih(UserConfirmation $k): string
{
    return URL::temporarySignedRoute('konfirmasi-user.select', $k->expires_at, ['konfirmasi' => $k->id]);
}

test('user membuka tautan tanpa login dan melihat kandidat beserta catatan', function () {
    $konfirmasi = batchUji();

    $this->get(tautanLihat($konfirmasi))
        ->assertOk()
        ->assertSee('Catatan kandidat 1')
        ->assertSee('Catatan kandidat 2');
});

test('tautan dengan tanda tangan yang diotak-atik ditolak', function () {
    $konfirmasi = batchUji();

    $this->get(tautanLihat($konfirmasi).'x')->assertForbidden();
    $this->get(route('konfirmasi-user.show', $konfirmasi))->assertForbidden();
});

test('user memilih satu kandidat', function () {
    $konfirmasi = batchUji();
    $pilihan = $konfirmasi->items->first()->application_id;
    $lainnya = $konfirmasi->items->last()->application;

    $this->post(tautanPilih($konfirmasi), ['application_id' => $pilihan])->assertOk();

    $konfirmasi->refresh();
    expect($konfirmasi->selected_application_id)->toBe($pilihan)
        ->and($konfirmasi->status)->toBe('selesai')
        ->and($konfirmasi->dipilih_oleh)->toBe('user')
        ->and($konfirmasi->confirmed_at)->not->toBeNull();

    // Kandidat lain sengaja tidak disentuh
    expect($lainnya->fresh()->status)->toBe('konfirmasi user');
});

test('klik kedua tidak mengubah pilihan pertama', function () {
    $konfirmasi = batchUji();
    $pertama = $konfirmasi->items->first()->application_id;
    $kedua = $konfirmasi->items->last()->application_id;

    $this->post(tautanPilih($konfirmasi), ['application_id' => $pertama]);
    $this->post(tautanPilih($konfirmasi), ['application_id' => $kedua])->assertOk();

    expect($konfirmasi->fresh()->selected_application_id)->toBe($pertama);
});

test('kandidat di luar kelompok tidak bisa dipilih', function () {
    $konfirmasi = batchUji();
    $asing = lamaranPada(lowonganUji());

    $this->post(tautanPilih($konfirmasi), ['application_id' => $asing->id])->assertOk();

    expect($konfirmasi->fresh()->selected_application_id)->toBeNull();
});

// Dua lapis penjagaan waktu, dan keduanya berbeda perilaku -- keduanya benar:
//
//   1. Masa berlaku TANDA TANGAN tertanam di URL saat dibuat. Kalau sudah lewat,
//      Laravel menolak duluan dengan 403, controller tidak pernah jalan.
//   2. Kolom expires_at di database. Kalau dimajukan ke masa lalu sementara tanda
//      tangannya masih sah, controller yang menahan -- halaman tetap tampil dengan
//      pesan ramah, bukan error keras.
test('tautan yang tanda tangannya sudah lewat ditolak', function () {
    $konfirmasi = batchUji();

    $tautanBasi = URL::temporarySignedRoute(
        'konfirmasi-user.select', now()->subMinute(), ['konfirmasi' => $konfirmasi->id]
    );

    $this->post($tautanBasi, ['application_id' => $konfirmasi->items->first()->application_id])
        ->assertForbidden();

    expect($konfirmasi->fresh()->selected_application_id)->toBeNull();
});

test('masa berlaku yang sudah habis memberi pesan, bukan error', function () {
    $konfirmasi = batchUji();
    $tautan = tautanPilih($konfirmasi);

    $konfirmasi->update(['expires_at' => now()->subDay()]);

    $this->post($tautan, ['application_id' => $konfirmasi->items->first()->application_id])
        ->assertOk()
        ->assertSee('Masa berlaku tautan ini sudah habis.');

    expect($konfirmasi->fresh()->selected_application_id)->toBeNull();
});

// ---------------------------------------------------------------- admin manual

test('admin bisa menandai terpilih tanpa menunggu user', function () {
    $konfirmasi = batchUji();
    $pilihan = $konfirmasi->items->first()->application_id;
    $admin = superadminKonfirmasi();

    $this->actingAs($admin, 'admin')
        ->post(route('admin.user-confirmations.pilih', $konfirmasi), ['application_id' => $pilihan])
        ->assertRedirect();

    $konfirmasi->refresh();
    expect($konfirmasi->selected_application_id)->toBe($pilihan)
        ->and($konfirmasi->dipilih_oleh)->toBe('admin')
        ->and($konfirmasi->dipilih_admin_id)->toBe($admin->id)
        ->and($konfirmasi->status)->toBe('selesai');
});

test('admin bisa mengganti pilihan yang sudah dibuat user', function () {
    $konfirmasi = batchUji();
    $pertama = $konfirmasi->items->first()->application_id;
    $kedua = $konfirmasi->items->last()->application_id;

    $this->post(tautanPilih($konfirmasi), ['application_id' => $pertama]);
    expect($konfirmasi->fresh()->dipilih_oleh)->toBe('user');

    $this->actingAs(superadminKonfirmasi(), 'admin')
        ->post(route('admin.user-confirmations.pilih', $konfirmasi), ['application_id' => $kedua]);

    $konfirmasi->refresh();
    expect($konfirmasi->selected_application_id)->toBe($kedua)
        ->and($konfirmasi->dipilih_oleh)->toBe('admin');
});

test('keadaan konfirmasi terbaca dari sisi lamaran', function () {
    $konfirmasi = batchUji();
    $terpilih = $konfirmasi->items->first()->application;
    $tidak = $konfirmasi->items->last()->application;

    expect($terpilih->fresh()->keadaanKonfirmasi())->toBe('menunggu');

    $this->post(tautanPilih($konfirmasi), ['application_id' => $terpilih->id]);

    expect($terpilih->fresh()->keadaanKonfirmasi())->toBe('terpilih')
        ->and($tidak->fresh()->keadaanKonfirmasi())->toBe('tidak');
});

// Bug yang pernah terjadi: dropdown modal dibangun dari pipelines() mentah, sedangkan
// validasi memakai selectableFor() yang sudah menyaring bulk_only. Akibatnya modal
// menawarkan "Konfirmasi User", admin memilihnya, lalu ditolak server dengan pesan
// membingungkan. Dua daftar ini harus sepakat.
test('daftar tahap modal dan daftar yang diterima validasi sepakat', function (string $kategori) {
    $modal = App\Models\RecruitmentStage::selectablePipelines()[$kategori];
    $sah = App\Models\RecruitmentStage::selectableFor($kategori);

    foreach ($modal as $tahap) {
        expect($sah)->toContain($tahap);
    }

    expect($modal)->not->toContain('konfirmasi user');
})->with(['Profesional', 'Management Trainee', 'Magang']);

// Dropdown filter justru HARUS memuatnya -- admin perlu bisa menyaring siapa saja
// yang sedang menunggu keputusan user.
test('daftar filter tetap memuat tahap bulk_only', function () {
    expect(App\Models\RecruitmentStage::pipelines()['Profesional'])->toContain('konfirmasi user');
});
