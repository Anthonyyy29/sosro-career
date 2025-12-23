@extends('admin.layout')

@section('content')
<h1 class="text-3xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white p-6 shadow rounded-lg">
        <h2 class="text-lg font-semibold">Total Pelamar</h2>
        <p class="text-3xl mt-2 font-bold">123</p>
    </div>

    <div class="bg-white p-6 shadow rounded-lg">
        <h2 class="text-lg font-semibold">Lowongan Aktif</h2>
        <p class="text-3xl mt-2 font-bold">8</p>
    </div>

    <div class="bg-white p-6 shadow rounded-lg">
        <h2 class="text-lg font-semibold">Proses Seleksi</h2>
        <p class="text-3xl mt-2 font-bold">15</p>
    </div>

</div>
@endsection
