<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LowonganController;
use App\Http\Controllers\Admin\ApplicantController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/profile', function () {
    return view('admin.profile');
})->name('profile');

Route::patch(
    'lowongan/{lowongan}/toggle-status',
    [LowonganController::class, 'toggleStatus']
)->name('lowongan.toggle-status');

// CRUD Lowongan
Route::resource('lowongan', LowonganController::class)
    ->except(['show', 'create', 'edit']);


Route::get('/applicants', [ApplicantController::class, 'index'])
    ->name('applicants');
