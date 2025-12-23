<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;

// --- 1. HALAMAN PUBLIK (Akses Tanpa Login) ---
Route::get('/', [PageController::class, 'beranda'])->name('beranda');
Route::get('/lowongan', [PageController::class, 'lowongan'])->name('lowongan'); // Halaman daftar Jobcard
Route::get('/program-kami', [PageController::class, 'program'])->name('program');
Route::get('/tentang-kami', [PageController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');

// --- 2. AUTH USER (Breeze - Pelamar) ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

// --- 3. AUTH ADMIN (Hanya Form Login & Logout) ---
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout')->middleware('auth:admin');

// --- 4. ADMIN DASHBOARD (Import dari admin.php) ---
// Kita bungkus semua yang ada di admin.php dengan middleware auth:admin di sini
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    require __DIR__.'/admin.php';
});

// Auth bawaan Breeze (Register, Login User, dll)
require __DIR__.'/auth.php';