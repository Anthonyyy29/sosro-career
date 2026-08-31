<?php


use illuminate\support\Facades\Route;

use App\Http\Controllers\PageController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;


// // Refactor
// Guest
Route::get('/', [PageController::class, 'lowongan'])->name('guest.home');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/lowongan', [PageController::class, 'lowongan'])->name('guest.job');

//Belum untuk di kerjakan sekarang. sambil jalan yg ini.

// Route::get('/program', [PageController::class, 'program'])->name('guest.program'); 
// Route::get('/tentang', [PageController::class, 'tentang'])->name('guest.about');
// Route::get('/kontak', [PageController::class, 'kontak'])->name('guest.contact');


// // Auth Guest

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('guest.register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('guest.login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});


// Auth Applicant
Route::middleware(['auth'])->group(function () {

    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::patch('verify-email/change', [EmailVerificationPromptController::class, 'updateEmail'])
        ->middleware('throttle:3,10')
        ->name('verification.change-email');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    });































// Admin
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Auth\AdminPasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\AdminNewPasswordController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;



// Route::get('/', [PageController::class, 'lowongan'])->name('lowongan');
// Route::get('/', [PageController::class, 'lowongan'])->name('landing');
// Route::get('/lowongan', [PageController::class, 'lowongan'])->name('lowongan');
// Route::get('/program-kami', [PageController::class, 'program'])->name('program');
// Route::get('/tentang-kami', [PageController::class, 'tentang'])->name('tentang');
// Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
// HAPUS1


// BARU: Route untuk publik :: Limit 3 kali kirim per 1 menit
/*
 | Halaman konfirmasi kandidat untuk user (pihak peminta lowongan).
 | Tanpa login: yang menjaga adalah tanda tangan pada URL (middleware 'signed')
 | plus batas waktu di kolom expires_at. Tautannya dikirim lewat email.
 */
Route::middleware('signed')->group(function () {
    Route::get('/konfirmasi-user/{konfirmasi}', [UserConfirmationPublicController::class, 'show'])
        ->name('konfirmasi-user.show');

    Route::post('/konfirmasi-user/{konfirmasi}', [UserConfirmationPublicController::class, 'select'])
        ->name('konfirmasi-user.select');
});

Route::post('/contact-us', [ContactController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('contact.store');

/*
|--------------------------------------------------------------------------
| 2. AUTH USER (Breeze)
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth', 'verified'])->group(function () {

//     Route::get('/dashboard', function () {
//         return redirect()->route('applicant.dashboard');
//     })->name('dashboard');

//     Route::controller(UserProfileController::class)->group(function () {
//         Route::get('/profile', 'edit')->name('profile.edit');
//         Route::patch('/profile', 'update')->name('profile.update');
//         Route::delete('/profile', 'destroy')->name('profile.destroy');
//     });
// });


/*
|--------------------------------------------------------------------------
| 3. AREA APPLICANT
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth'])
//     ->prefix('applicant')
//     ->name('applicant.')
//     ->group(function () {

//         Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//         // Profile Applicant (SEMUA DATA + DOKUMEN ADA DI SINI)
//         Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
//         Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
//         Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
//         Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
//         Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
//         Route::patch('/profile/draft', [ProfileController::class, 'saveDraft'])->name('profile.draft');

//         Route::get('/profile/download', [ProfileController::class, 'downloadPdf'])->name('profile.download');

//         /*
//         |--------------------------------------------------------------------------
//         | DOKUMEN (terpisah dari form biodata, wajib setelah lamaran accepted)
//         |--------------------------------------------------------------------------
//         */
//         Route::get('/documents', [DocumentController::class, 'edit'])->name('documents.edit');
//         Route::post('/documents', [DocumentController::class, 'update'])->name('documents.update');

//         /*
//             |--------------------------------------------------------------------------
//             | RIWAYAT LAMARAN
//             |--------------------------------------------------------------------------
//             */
//         Route::get('/lamaran-saya', [ApplicationController::class, 'index'])
//             ->name('applications.index');
//     });


/*
|--------------------------------------------------------------------------
| 4. APPLY LOWONGAN (WAJIB PROFIL LENGKAP)
|--------------------------------------------------------------------------
*/
Route::post('/apply/{lowongan}', [ApplyController::class, 'store'])
    ->middleware(['auth', 'applicant.complete'])
    ->name('jobs.apply');


/*
|--------------------------------------------------------------------------
| 5. ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout')->middleware('auth:admin');

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

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {

    Route::get('lowongan/{lowongan}/applicants', [App\Http\Controllers\Admin\ApplicantController::class, 'byLowongan'])
        ->name('lowongan.applicants');

    Route::get('/applicants', [ApplicantController::class, 'index'])
        ->name('applicants.index');

    Route::get('/applicants/{application}/detail', [ApplicantController::class, 'show'])
        ->name('applicants.show');

    // Route untuk proses Bulk Update
    Route::post('/applicants/bulk-update', [ApplicantController::class, 'bulkUpdate'])->name('applicants.bulkUpdate');
    Route::post('/applicants/bulk-prepare', [ApplicantController::class, 'bulkPrepare'])->name('applicants.bulkPrepare');
    Route::post('/applicants/bulk-process', [ApplicantController::class, 'bulkProcess'])->name('applicants.bulkProcess');

    Route::post('/user-confirmations', [UserConfirmationController::class, 'store'])
        ->name('user-confirmations.store');
    Route::post('/user-confirmations/{konfirmasi}/pilih', [UserConfirmationController::class, 'pilihManual'])
        ->name('user-confirmations.pilih');

    Route::post('/applications/update-stage', [ApplicantController::class, 'updateStage'])
        ->name('applications.update-stage');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    Route::get('laporan/export', [LaporanController::class, 'export'])
        ->name('laporan.export');

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    Route::put('/users/{id}', [UserController::class, 'update'])
        ->name('users.update');

    Route::delete('/users/{id}', [UserController::class, 'destroy'])
        ->name('users.destroy');

    Route::get('/users/download-profile/{applicantId}', [ApplicantController::class, 'downloadProfilePdf'])
        ->name('users.downloadProfile');

    Route::get('/messages', [ContactController::class, 'index'])
        ->name('kontak.index');

    Route::post('/messages/{contact}/assign', [ContactController::class, 'assign'])
        ->name('kontak.assign');

    Route::delete('/kontak/{contact}', [ContactController::class, 'destroy'])
        ->name('kontak.destroy');

    Route::post('/kontak/{contact}/mark-replied', [ContactController::class, 'markAsReplied'])
        ->name('kontak.mark-replied');

    require __DIR__ . '/admin.php';
});


/*
|--------------------------------------------------------------------------
| Breeze Auth Routes
|--------------------------------------------------------------------------
*/
// require __DIR__ . '/auth.php';
