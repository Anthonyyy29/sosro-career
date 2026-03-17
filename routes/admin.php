<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LowonganController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\ProfileController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Grouping agar lebih rapi
Route::prefix('profile')->name('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index']); // Akan jadi route('admin.profile')
    Route::post('/upload-photo', [ProfileController::class, 'updatePhoto'])->name('.upload-photo');
});

Route::patch(
    'lowongan/{lowongan}/toggle-status',
    [LowonganController::class, 'toggleStatus']
)->name('lowongan.toggle-status');

// CRUD Lowongan
Route::resource('lowongan', LowonganController::class)
    ->except(['show', 'create', 'edit']);


Route::get('/applicants', [ApplicantController::class, 'index'])
    ->name('applicants');

Route::get('/applicants/{application}/pdf', [ApplicantController::class, 'downloadPdf'])
    ->name('applicants.pdf');