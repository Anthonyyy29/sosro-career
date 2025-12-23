@extends('admin.layout')

@section('content')

<div class="bg-white p-6 rounded-lg shadow-md max-w-3xl mx-auto">

    <h2 class="text-xl font-bold mb-4">Buat Lowongan Baru</h2>

    <form action="{{ route('admin.lowongan.store') }}" method="POST">
        @csrf

        @include('admin.lowongan.form')

        <div class="mt-6">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Simpan
            </button>
        </div>
    </form>

</div>

@endsection
