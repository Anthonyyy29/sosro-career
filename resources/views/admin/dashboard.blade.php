@extends('admin.layout')

@section('page-title', 'Dashboard')

@section('content')

{{-- Welcome Header --}}
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Ringkasan Rekrutmen</h2>
</div>

{{-- ================= SUMMARY CARDS ================= --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Card Total --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest bg-blue-50 px-2 py-1 rounded-lg">Total</span>
        </div>
        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Pelamar</h3>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $rekap['total'] }}</p>
    </div>

    {{-- Card Pending --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-[10px] font-bold text-amber-500 uppercase tracking-widest bg-amber-50 px-2 py-1 rounded-lg">Pending</span>
        </div>
        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Dalam Proses</h3>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $rekap['proses'] }}</p>
    </div>

    {{-- Card Accepted --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest bg-emerald-50 px-2 py-1 rounded-lg">Accepted</span>
        </div>
        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Diterima</h3>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $rekap['diterima'] }}</p>
    </div>

    {{-- Card Rejected --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-[10px] font-bold text-rose-500 uppercase tracking-widest bg-rose-50 px-2 py-1 rounded-lg">Rejected</span>
        </div>
        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Ditolak</h3>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $rekap['ditolak'] }}</p>
    </div>
</div>

{{-- ================= MAIN CONTENT GRID ================= --}}
{{-- ================= MAIN CONTENT GRID ================= --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    {{-- SISI KIRI (Informasi Lowongan & Pipeline) --}}
    <div class="flex flex-col gap-6">
        
        {{-- 1. KARTU INFORMASI LOWONGAN (Diatas) --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Status Lowongan</h2>
                    <p class="text-xs text-gray-400 mt-1">Distribusi lowongan aktif</p>
                </div>
                <a href="{{ route('admin.lowongan.index') }}" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-3 gap-2">
                @foreach(['Profesional', 'Management Trainee', 'Magang'] as $cat)
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 flex flex-col items-center justify-center transition-all">
                    <p class="text-[9px] text-gray-400 font-bold uppercase truncate w-full text-center mb-1">{{ $cat }}</p>
                    <p class="text-lg font-bold text-gray-800">{{ $lowonganByKategori->get($cat) ?? 0 }}</p>
                </div>
                @endforeach
            </div>

            <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 text-center">
                <p class="text-xs text-gray-500 font-medium">Total Lowongan Aktif Saat Ini</p>
                <p class="text-2xl font-bold text-green-500">{{ $lowonganActive }}</p>
            </div>
        </div>

        {{-- 2. KARTU PIPELINE TAHAPAN (Dibawah - Menggunakan Desain ProgressBar) --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm" x-data="{ tab: 'Profesional' }">
            <div class="mb-6">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Pipeline Tahapan</h2>
                <p class="text-xs text-gray-400 mt-1">Antrean proses kandidat per kategori</p>
            </div>
            
            <div class="flex bg-gray-50 p-1 rounded-xl mb-6 border border-gray-100">
                @foreach(['Profesional', 'Management Trainee', 'Magang'] as $cat)
                <button @click="tab = '{{ $cat }}'" 
                    :class="tab === '{{ $cat }}' ? 'bg-white shadow-sm text-red-600 ring-1 ring-black/5' : 'text-gray-400 hover:text-gray-600'"
                    class="flex-1 py-1.5 text-[9px] font-bold rounded-lg transition-all uppercase tracking-tighter">
                    {{ $cat }}
                </button>
                @endforeach
            </div>

            <div class="space-y-5">
                @foreach(['Profesional', 'Management Trainee', 'Magang'] as $cat)
                <div x-show="tab === '{{ $cat }}'" x-transition:enter="transition ease-out duration-200">
                    @php $items = $pipelineData->get($cat); @endphp

                    @if($items && $items->count() > 0)
                        <div class="space-y-4">
                            @foreach($items as $item)
                            <div>
                                <div class="flex justify-between text-[10px] mb-1.5">
                                    <span class="font-bold text-gray-600 uppercase tracking-tight">{{ str_replace('_', ' ', $item->status) }}</span>
                                    <span class="font-bold text-red-600">{{ $item->total }} Pelamar</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    @php
                                        $totalInCat = $items->sum('total');
                                        $perc = ($item->total / $totalInCat) * 100;
                                    @endphp
                                    <div class="bg-red-500 h-full rounded-full transition-all duration-500" style="width: {{ $perc }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center bg-gray-50/50 rounded-xl border border-dashed border-gray-100">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Tidak ada antrean</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SISI KANAN (Pelamar Terbaru - Tetap 2 Kolom) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-800 uppercase tracking-wider">Pelamar Terbaru</h2>
                <p class="text-sm text-gray-400 mt-1">Log lamaran masuk real-time</p>
            </div>
            <a href="{{ route('admin.applicants') }}" class="text-xs font-bold text-red-600 px-4 py-2 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kandidat</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Posisi & Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentActivities as $applicant)
                    <tr class="group hover:bg-gray-50/80 cursor-pointer transition-all" onclick="window.open('{{ route('admin.applicants.show', $applicant->id) }}', '_blank')">
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500 font-bold text-xs group-hover:bg-red-600 group-hover:text-white transition-all shadow-sm">
                                    {{ strtoupper(substr($applicant->applicant->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-800 leading-none mb-1">{{ $applicant->applicant->user->name ?? 'User' }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $applicant->applicant->user->email ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-700 leading-none mb-1 group-hover:text-red-600 transition-colors">{{ $applicant->lowongan->judul_lowongan ?? 'Unknown' }}</span>
                                <span class="text-[9px] text-gray-400 uppercase font-medium tracking-tighter italic">{{ $applicant->lowongan->kategori ?? 'Umum' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'accepted' => 'bg-emerald-50 text-emerald-600 ring-emerald-600/10',
                                    'rejected' => 'bg-rose-50 text-rose-600 ring-rose-600/10',
                                    'pending'  => 'bg-amber-50 text-amber-600 ring-amber-600/10'
                                ];
                                $class = $statusClasses[$applicant->status] ?? 'bg-slate-50 text-slate-600 ring-slate-600/10';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold uppercase ring-1 ring-inset {{ $class }}">
                                {{ $applicant->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[11px] font-bold text-gray-600 block leading-none">{{ $applicant->created_at->format('d/m/y') }}</span>
                            <span class="text-[9px] text-gray-400 font-medium lowercase">{{ $applicant->created_at->diffForHumans() }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-xs text-gray-400">Belum ada aktivitas lamaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection