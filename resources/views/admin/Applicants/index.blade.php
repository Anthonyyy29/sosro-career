@extends('admin.layout')

@section('content')

<div class="bg-white p-6 rounded-lg shadow-md">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Daftar Pelamar</h2>

        <div class="flex gap-3">
            {{-- <a href="{{ route('admin.applicants.export.pdf') }}" --}}
            <a href="#"
                class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                Export PDF
            </a>

            {{-- <a href="{{ route('admin.applicants.export.excel') }}" --}}
            <a href="#"
                class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                Export Excel
            </a>
        </div>
    </div>

    <!-- Card Table -->
    {{-- <div class="overflow-x-auto border rounded-lg"> --}}
    <div>
        <table class="min-w-full text-left text-sm" id="table">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Posisi Dilamar</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-center">Opsi</th>
                </tr>
            </thead>

            {{-- MASIH BELUM SINKRON ANTARA HEAD TABLE DENGAN ISI TABLE --}}
            <tbody>
                @forelse($applicants as $item)
                    <tr class="border-b">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $item->name }}</td>
                        <td class="px-4 py-3">{{ $item->job_id }}</td>
                        <td class="px-4 py-3">{{ $item->email }}</td>
                        <td class="px-4 py-3">{{ $item->status }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-gray-500">-</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            Belum ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection
