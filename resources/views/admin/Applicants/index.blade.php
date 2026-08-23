@extends('admin.layout')

@section('content')

<div x-data="{
        statusModalOpen: false,
        selectedAppId: null,
        nextStatus: '',
        category: '',
        pipelines: {{ Js::from(App\Models\RecruitmentStage::pipelines()) }},
        stageLabels: {{ Js::from(App\Models\RecruitmentStage::labels()) }},
        stageColors: {{ Js::from(App\Models\RecruitmentStage::colors()) }},
        universalDestinations: {{ Js::from(App\Models\RecruitmentStage::universalDestinations()) }},
        openStatusModal(id, currentStatus, category) {
            this.selectedAppId = id;
            this.category = category;
            this.nextStatus = '';
            this.statusModalOpen = true;
        }
    }">
    @if ($errors->any())
    <div class="fixed top-5 right-5 z-[100] bg-red-600 text-white p-4 rounded-lg shadow-xl">
        <strong>Terjadi Kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Kelola Pelamar</h1>

                @isset($lowongan)
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-sm text-gray-500 font-medium">Lowongan Terpilih</span>
                        <p class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[12px] font-bold uppercase rounded-md tracking-wider">{{ $lowongan->judul_lowongan }}</p>
                    </div>
                @endisset
            </div>

            <div class="flex items-center gap-2">
                @isset($lowongan)
                <a href="{{ route('admin.lowongan.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 font-semibold rounded-xl text-sm hover:bg-gray-50 hover:text-red-600 transition-all shadow-sm group flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                @endisset
            </div>
        </div>

        {{-- Card info --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pelamar</span>
                <div class="flex items-end justify-between mt-2">
                    <span class="text-2xl font-bold text-gray-800">{{ $applications->count() }}</span>
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dalam Proses</span>
                <div class="flex items-end justify-between mt-2">
                    <span class="text-2xl font-bold text-gray-800">{{ $applications->whereNotIn('status', ['accepted', 'rejected'])->count() }}</span>
                    <div class="p-2 bg-yellow-50 rounded-lg text-yellow-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider text-green-600">Lolos</span>
                <div class="flex items-end justify-between mt-2">
                    <span class="text-2xl font-bold text-green-600">{{ $applications->where('status','accepted')->count() }}</span>
                    <div class="p-2 bg-green-50 rounded-lg text-green-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider text-red-600">Gagal</span>
                <div class="flex items-end justify-between mt-2">
                    <span class="text-2xl font-bold text-red-600">{{ $applications->where('status','rejected')->count() }}</span>
                    <div class="p-2 bg-red-50 rounded-lg text-red-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Fitur filter --}}
        <div x-data="{ 
            showFilter: {{ (request('category') || request('status') || request('start_date')) ? 'true' : 'false' }}, 
            selectedCat: '{{ request('category', '') }}' 
            }" class="mb-8">

            <div class="flex flex-wrap justify-between items-center gap-4 mb-5">
                <div class="flex items-center gap-3">
                    <button @click="showFilter = !showFilter" 
                            class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:shadow-md transition-all active:scale-95">
                        <svg class="w-4 h-4 transition-transform duration-300" :class="showFilter ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <span>Filter & Periode</span>
                        @if(request('category') || request('status') || request('start_date'))
                            <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                        @endif
                    </button>
                    
                    @if(request('category') || request('status'))
                        <div class="hidden md:flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-lg border border-blue-100">
                            <span class="text-[11px] font-bold text-blue-700 uppercase tracking-tight">
                                {{ request('category') ?? 'Semua' }} ➔ {{ str_replace('_', ' ', request('status') ?? 'Semua Tahapan') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div x-show="showFilter" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="bg-white p-7 rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50" x-cloak>
                
                <form action="{{ url()->current() }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em] mb-2 ml-1">Kategori Lowongan</label>
                            <select name="category" x-model="selectedCat" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50/50 transition-all">
                                <option value="">Semua Kategori</option>
                                <option value="Profesional">Profesional</option>
                                <option value="Management Trainee">Management Trainee</option>
                                <option value="Magang">Magang / Motoris</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em] mb-2 ml-1">Tahapan Seleksi</label>
                            <select name="status" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50/50 transition-all">
                                <option value="">Semua Tahapan</option>
                                
                                @php
                                    $stageLabels = App\Models\RecruitmentStage::labels();
                                    $tahapAwal = config('recruitment.initial');
                                @endphp

                                {{-- Tahap awal, di luar urutan pipeline --}}
                                <option value="{{ $tahapAwal }}" {{ request('status') == $tahapAwal ? 'selected' : '' }}>{{ $stageLabels[$tahapAwal] ?? $tahapAwal }} ({{ $stats[$tahapAwal] ?? 0 }})</option>

                                @foreach (App\Models\RecruitmentStage::pipelines() as $kategori => $stages)
                                    <optgroup label="Alur {{ $kategori }}" x-show="selectedCat === '{{ $kategori }}'">
                                        @foreach ($stages as $stage)
                                            <option value="{{ $stage }}" {{ request('status') == $stage ? 'selected' : '' }}>{{ $stageLabels[$stage] ?? $stage }} ({{ $stats[$stage] ?? 0 }})</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach

                                {{-- Tahap akhir (accepted/rejected), berlaku di semua kategori --}}
                                @foreach (App\Models\RecruitmentStage::universalDestinations() as $stage)
                                    <option value="{{ $stage }}" {{ request('status') == $stage ? 'selected' : '' }}>{{ $stageLabels[$stage] ?? $stage }} ({{ $stats[$stage] ?? 0 }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em] mb-2 ml-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50/50">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.1em] mb-2 ml-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50/50">
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ url()->current() }}" class="px-6 py-3 bg-gray-50 text-gray-500 rounded-xl font-bold text-sm hover:bg-gray-100 transition-all">
                            Reset Filter
                        </a>
                        <button type="submit" class="px-10 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Fitur Update Masal --}}
        <form id="bulkForm" method="POST" action="{{ route('admin.applicants.bulkPrepare') }}">
            @csrf
            <div id="hiddenIdsContainer"></div>

            <div id="bulkUpdateBtn" class="hidden flex items-center gap-3 mb-4">
                <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Pilih Status</option>
                    @foreach (App\Models\RecruitmentStage::bulkUpdateStages() as $stage)
                        <option value="{{ $stage }}" @class(['bg-red-200' => $stage === 'rejected'])>{{ App\Models\RecruitmentStage::labels()[$stage] ?? $stage }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                    Update Massal
                </button>
            </div>
        </form>
        {{-- End FItur Update Masal --}}

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto p-3">
                <table class="min-w-full text-sm" id="table">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="py-4 px-4 text-[11px]"><input type="checkbox" id="selectAll" class="rounded border-gray-300"></th>
                            <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">No</th>
                            <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Kandidat</th>
                            <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Posisi</th>
                            <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Kategori</th>
                            <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Tanggal Lamar</th>
                            <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Status</th>
                            <th class="py-4 px-6 text-center font-bold text-gray-500 uppercase tracking-wider text-[11px]">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        @foreach($applications as $application)
                            <tr class="group hover:bg-blue-50/50 transition-all duration-100">
                                
                                <td class="px-4 py-4">
                                    <input 
                                        type="checkbox" 
                                        class="applicant-checkbox rounded border-gray-300"
                                        value="{{ $application->id }}">
                                </td>

                                <td class="px-6 py-4 text-gray-400 font-medium">{{ $loop->iteration }}</td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 group-hover:text-blue-700 transition-colors">{{ $application->applicant->user->name ?? '-' }}</span>
                                        <span class="text-xs text-gray-400">{{ $application->applicant->user->email ?? '-' }}</span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <span class="text-gray-600 font-medium">{{ $application->lowongan->judul_lowongan ?? '-' }}</span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <span class="text-gray-600 font-medium">{{ $application->lowongan->kategori ?? '-' }}</span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-gray-700 font-medium">{{ optional($application->created_at)->locale('id')->translatedFormat('d M Y') }}</span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    @php
                                        $statusStyle = App\Models\RecruitmentStage::colors()[$application->status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                        $statusLabel = App\Models\RecruitmentStage::labels()[$application->status] ?? str_replace('_', ' ', $application->status);
                                    @endphp
                                    <button type="button" title="Ubah Status"
                                            @click="openStatusModal({{ $application->id }}, '{{ $application->status }}', '{{ $application->lowongan->kategori }}')"
                                            class="group flex items-center gap-2 p-1 rounded-lg transition-all hover:ring-2 hover:ring-blue-500/20">

                                        <span class="px-3 py-1 rounded-lg text-[11px] font-bold border {{ $statusStyle }} uppercase tracking-wide">
                                            {{ $statusLabel }}
                                        </span>

                                        <svg class="w-3 h-3 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                </td>

                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.applicants.show', $application->id) }}" target="_blank" class="text-[12px] italic text-blue-500 hover:underline">Lihat Biodata</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($applications->isEmpty())
                <div class="py-12 flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <p class="text-sm">Belum ada pelamar untuk lowongan ini.</p>
                </div>
            @endif
        </div>
    </div>

    <div x-show="statusModalOpen" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

            <div class="relative bg-white rounded-xl shadow-2xl max-w-lg w-full p-6 overflow-hidden transition-all text-left">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-bold text-gray-900">Update Tahapan Seleksi</h3>
                    <button @click="statusModalOpen = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <form action="{{ route('admin.applications.update-stage') }}" method="POST">
                    @csrf
                    <input type="hidden" name="application_id" :value="selectedAppId">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Pindah ke Tahap:</label>
                            <select name="next_status" x-model="nextStatus" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled>-- Pilih Tahapan --</option>
                                {{-- Alur tahap sesuai kategori lowongan, sumbernya config/recruitment.php --}}
                                <template x-if="pipelines[category]">
                                    <optgroup :label="'Alur ' + category">
                                        <template x-for="stage in (pipelines[category] || [])" :key="stage">
                                            <option :value="stage" x-text="stageLabels[stage] || stage"></option>
                                        </template>
                                    </optgroup>
                                </template>

                                {{-- Tahap akhir (accepted/rejected): di luar urutan, bisa dipilih kapan saja --}}
                                <template x-for="stage in universalDestinations" :key="stage">
                                    <option :value="stage" :class="stageColors[stage]" x-text="stageLabels[stage] || stage"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Isian tambahan per tahap. Tiap tahap punya berkasnya sendiri di
                             resources/views/admin/applicants/stages/, dan yang disertakan
                             ditentukan kunci 'form' di config/recruitment.php. --}}
                        @foreach (App\Models\RecruitmentStage::formPartials() as $partialTahap)
                            @include($partialTahap)
                        @endforeach



                        {{-- Form Info Offering --}}


                        {{-- Form input Tes Lapangan (disabled: dead code, gak ada opsi manapun yang set nextStatus ke 'practical_test', digantikan 'simulasi')
                        <div x-show="nextStatus === 'practical_test'" x-transition class="p-4 bg-orange-50 rounded-xl border border-orange-100 space-y-3">
                            <p class="text-[11px] font-bold text-orange-600 uppercase">Informasi Tes Lapangan</p>
                            <div>
                                <label class="text-xs text-gray-500">Lokasi Pertemuan</label>
                                <input type="text" name="test_location" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" placeholder="Contoh: Gudang A / Parkiran Timur">
                            </div>
                        </div>
                        --}}


                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="statusModalOpen = false" class="px-5 py-2.5 text-sm font-bold text-gray-500">Batal</button>
                        <button type="submit" :disabled="!nextStatus" x-data="{loading:false}"
                                @click="loading=true"
                                :class="loading ? 'opacity-50 pointer-events-none':''"
                                class="px-5 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-black transition shadow-lg">
                            Konfirmasi & Kirim Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.applicant-checkbox');
            const bulkBtn = document.getElementById('bulkUpdateBtn');
            const bulkForm = document.getElementById('bulkForm');
            const hiddenContainer = document.getElementById('hiddenIdsContainer');

            // 1. Logic Pilih Semua
            selectAll?.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkButton();
            });

            // 2. Logic Centang Satuan
            checkboxes.forEach(cb => {
                cb.addEventListener('change', toggleBulkButton);
            });

            // 3. Fungsi Show/Hide Tombol Update
            function toggleBulkButton() {
                const checkedCount = document.querySelectorAll('.applicant-checkbox:checked').length;
                if (checkedCount > 0) {
                    bulkBtn.classList.remove('hidden');
                } else {
                    bulkBtn.classList.add('hidden');
                    if(selectAll) selectAll.checked = false;
                }
            }

            // 4. Logic Submit Form
            bulkForm.addEventListener('submit', function(e) {
                const selected = document.querySelectorAll('.applicant-checkbox:checked');
                const status = this.querySelector('select[name="status"]').value;

                if (selected.length === 0) {
                    alert('Pilih minimal satu pelamar!');
                    e.preventDefault();
                    return;
                }

                if (!status) {
                    alert('Pilih status terlebih dahulu!');
                    e.preventDefault();
                    return;
                }

                hiddenContainer.innerHTML = '';

                selected.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_ids[]';
                    input.value = cb.value;
                    hiddenContainer.appendChild(input);
                });
            });
        });
    </script>

@endsection