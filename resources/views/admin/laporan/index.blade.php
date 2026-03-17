@extends('admin.layout')

@section('content')
<div class="no-print">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Laporan Rekap Rekrutmen</h1>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-sm font-medium text-gray-400 uppercase">Total Pelamar</p>
            <p class="text-3xl font-bold text-gray-800">{{ $rekap['total'] }}</p>
        </div>
        <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 shadow-sm">
            <p class="text-sm font-medium text-blue-600 uppercase">Dalam Proses</p>
            <p class="text-3xl font-bold text-blue-700">{{ $rekap['proses'] }}</p>
        </div>
        <div class="bg-green-50 p-6 rounded-xl border border-green-100 shadow-sm">
            <p class="text-sm font-medium text-green-600 uppercase">Diterima</p>
            <p class="text-3xl font-bold text-green-700">{{ $rekap['diterima'] }}</p>
        </div>
        <div class="bg-red-50 p-6 rounded-xl border border-red-100 shadow-sm">
            <p class="text-sm font-medium text-red-600 uppercase">Ditolak</p>
            <p class="text-3xl font-bold text-red-700">{{ $rekap['ditolak'] }}</p>
        </div>
    </div>

    {{-- Filter Box --}}
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-8">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 items-end gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-xl border-gray-200 text-sm focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-xl border-gray-200 text-sm focus:ring-red-500 focus:border-red-500">
            </div>
            
            {{-- Filter Posisi --}}
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-2">Posisi</label>
                <select name="lowongan_id" class="w-full rounded-xl border-gray-200 text-sm focus:ring-red-500">
                    <option value="">Semua Posisi</option>
                    @foreach($listLowongan as $low)
                        <option value="{{ $low->id }}" {{ request('lowongan_id') == $low->id ? 'selected' : '' }}>
                            {{ $low->judul_lowongan }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status Dinamis --}}
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-2">Status</label>
                <select name="status" class="w-full rounded-xl border-gray-200 text-sm focus:ring-red-500">
                    <option value="">Semua Status</option>
                    @foreach($listStatus as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $st)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" title="Filter" class="bg-gray-800 text-white p-2.5 rounded-xl font-semibold text-sm hover:bg-black transition-all flex-1 flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                </button>
                
                {{-- Tombol Export Update --}}
                <a href="{{ route('admin.laporan.export', request()->query()) }}" 
                    title="Export Excel" class="bg-green-600 text-white p-2.5 rounded-xl hover:bg-green-700 transition shadow-md shadow-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </a>

                {{-- <button type="button" title="Print PDF" onclick="window.print()" class="bg-red-600 text-white p-2.5 rounded-xl hover:bg-red-700 transition-all flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                </button> --}}
                <button type="button" id="btn-print-custom" title="Print PDF" class="bg-red-600 text-white p-2.5 rounded-xl hover:bg-red-700 transition-all flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Table Laporan --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden print:shadow-none print:border-none">
    <div class="p-8 hidden print:block">
        <h1 class="text-2xl font-bold text-center">LAPORAN REKRUTMEN</h1>
        <p class="text-center text-gray-500 text-sm">Periode: {{ $startDate ?? 'Awal' }} s/d {{ $endDate ?? 'Sekarang' }}</p>
        <hr class="my-4 border-gray-800">
    </div>
    <div class="overflow-x-auto p-5">
        <table class="w-full text-sm border-collapse" id="tabel-laporan">
            <thead class="bg-gray-50 print:bg-white">
                <tr>
                    <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">No</th>
                    <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Tanggal</th>
                    <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Nama Pelamar</th>
                    <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Lowongan</th>
                    <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($applications as $app)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $app->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $app->applicant->user->name ?? 'User Terhapus' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $app->lowongan->judul_lowongan }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-[10px] font-bold uppercase">{{ $app->status }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        body { background: white; }
        .no-print { display: none !important; }
        .main-content { padding: 0 !important; }
        .sidebar { display: none !important; }
        header { display: none !important; }
        table { width: 100% !important; border: 1px solid #eee; }
    }
</style>
@endsection