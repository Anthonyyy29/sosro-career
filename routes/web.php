<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ApplyController;
use App\Http\Controllers\ContactController;

// Breeze (User)
use App\Http\Controllers\ProfileController as UserProfileController;

// Applicant
use App\Http\Controllers\Applicant\DashboardController;
use App\Http\Controllers\Applicant\ApplicationController;
use App\Http\Controllers\Applicant\ProfileController;

// Admin
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Auth\AdminPasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\AdminNewPasswordController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'lowongan'])->name('lowongan');
// Route::get('/', [PageController::class, 'lowongan'])->name('landing');
// Route::get('/lowongan', [PageController::class, 'lowongan'])->name('lowongan');
// Route::get('/program-kami', [PageController::class, 'program'])->name('program');
// Route::get('/tentang-kami', [PageController::class, 'tentang'])->name('tentang');
// Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');

// // // // // // // // // // // // // // // // // // // // // //
// DISABLE PUBLIC ROUTES UNTUK SEMENTARA UNTUK SOFT LAUNCHING  //
// // // // // // // // // // // // // // // // // // // // // //

// BARU: Route untuk publik :: Limit 3 kali kirim per 1 menit
Route::post('/contact-us', [ContactController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('contact.store');

/*
|--------------------------------------------------------------------------
| 2. AUTH USER (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('applicant.dashboard');
    })->name('dashboard');

    Route::controller(UserProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| 3. AREA APPLICANT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('applicant')
    ->name('applicant.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile Applicant (SEMUA DATA + DOKUMEN ADA DI SINI)
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
        Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/draft', [ProfileController::class, 'saveDraft'])->name('profile.draft');

        Route::get('/profile/download', [ProfileController::class, 'downloadPdf'])->name('profile.download');

            /*
            |--------------------------------------------------------------------------
            | RIWAYAT LAMARAN
            |--------------------------------------------------------------------------
            */
        Route::get('/lamaran-saya', [ApplicationController::class, 'index'])
        ->name('applications.index');
});


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
    Route::post('/applicants/bulk-process-interview', [ApplicantController::class, 'bulkProcessInterview'])->name('applicants.bulkProcessInterview');

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

    require __DIR__.'/admin.php';   
});


/*
|--------------------------------------------------------------------------
| Breeze Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
