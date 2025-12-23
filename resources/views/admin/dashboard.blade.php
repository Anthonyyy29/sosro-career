@extends('admin.layout')

{{-- Menentukan Judul Spesifik Halaman --}}
@section('page-title', 'Dashboard') 

{{-- Menentukan Konten Spesifik Halaman --}}
@section('content')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-3xl font-semibold text-gray-700">Selamat Datang Admin!</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    {{-- Contoh Card Informasi --}}
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-orange-500">
        <h2 class="text-xl font-bold mb-2 text-gray-800">Total Pengguna</h2>
        <p class="text-3xl font-extrabold text-orange-600">1,250</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-red-500">
        <h2 class="text-xl font-bold mb-2 text-gray-800">Job Aktif</h2>
        <p class="text-3xl font-extrabold text-red-600">45</p>
    </div>
    {{-- Tambahkan lebih banyak card di sini --}}
</div>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Ringkasan Aktivitas Terbaru</h2>
    <p>... Tempat untuk grafik atau tabel aktivitas ...</p>
</div>

@endsection