<x-guest-layout>
    <style>
        @media print {
            /* Sembunyikan elemen yang tidak perlu ada di dokumen resmi */
            nav, .nav-buttons, .sidebar, .no-print, button, footer {
                display: none !important;
            }

            /* Hilangkan bayangan kartu dan border yang terlalu tebal */
            .shadow-xl, .rounded-3xl {
                box-shadow: none !important;
                border: none !important;
            }

            /* Pastikan background warna/abu-abu tipis tetap muncul di PDF */
            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
            }

            .bg-gray-50 {
                background-color: #f9fafb !important;
                border: 1px solid #f3f4f6 !important;
            }

            /* Atur margin kertas A4 */
            @page {
                size: A4;
                margin: 1.5cm;
            }
        }
    </style>
    <div class="hidden print:block mb-8 border-b-2 border-black pb-4 text-center">
        <h1 class="text-2xl font-bold uppercase tracking-widest">Biodata Calon Karyawan</h1>
        <p class="text-sm font-medium italic">Data ini dicetak melalui sistem recruitment resmi perusahaan</p>
    </div>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Action Buttons (Top) --}}
        <div class="mb-6 flex justify-between items-center no-print">
            <h2 class="text-xl font-bold text-gray-800 border-l-4 border-red-600 pl-3">Resume <span class="text-red-600 italic">Preview</span></h2>
            <div class="flex gap-3">
                <a href="{{ route('applicant.profile.download') }}" 
                target="_blank" 
                class="px-6 py-2 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 transition-all flex items-center gap-2 shadow-lg relative z-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    DOWNLOAD BIODATA (PDF)
                </a>
                <a href="{{ route('applicant.profile.edit') }}" class="px-4 py-2 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition">Edit Data</a>
            </div>
        </div>

        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden min-h-screen">
            {{-- 1. HEADER RINGKAS (Layout Optimized & Professional) --}}
            <div class="bg-slate-50 border-b border-gray-200 p-8">
                <div class="flex flex-col md:flex-row gap-10 items-center md:items-start">
                    
                    {{-- Foto Profil --}}
                    <div class="w-32 h-40 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0 border-4 border-white shadow-md mx-auto md:mx-0 relative">
                        @php $docsByType = $applicant->documents->keyBy('type'); @endphp
                        @if($docsByType->get('foto'))
                            <img src="{{ asset('storage/' . $docsByType->get('foto')->file_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="h-full flex flex-col items-center justify-center text-gray-400 text-[10px] uppercase p-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Foto 3x4
                            </div>
                        @endif
                    </div>

                    {{-- Konten Identitas --}}
                    <div class="flex-1 flex flex-col min-h-[160px] w-full">
                        {{-- Nama & Utama --}}
                        <div class="space-y-1 text-center md:text-left mb-6">
                            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tight leading-tight">
                                {{ Auth::user()->name }}
                            </h1>
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <div class="flex items-center gap-1.5 bg-red-50 px-3 py-1 rounded-full border border-red-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                    <p class="text-sm font-bold text-red-600 tracking-wide">{{ $applicant->profile->phone }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Info & Sosial Media (Baris 1) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            {{-- Email --}}
                            <div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-gray-100 shadow-sm">
                                <div class="w-8 h-8 flex items-center justify-center bg-slate-50 rounded-md text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="truncate">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Email Address</p>
                                    <p class="text-xs font-semibold text-gray-600 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>

                            {{-- Instagram --}}
                            <div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-gray-100 shadow-sm">
                                <div class="w-8 h-8 flex items-center justify-center bg-slate-50 rounded-md text-pink-500/70">
                                    <span class="text-[10px] font-black uppercase tracking-tighter">IG</span>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Instagram</p>
                                    <p class="text-xs font-semibold text-gray-600">{{ $applicant->profile->instagram ?? '-' }}</p>
                                </div>
                            </div>

                            {{-- LinkedIn --}}
                            <div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-gray-100 shadow-sm">
                                <div class="w-8 h-8 flex items-center justify-center bg-slate-50 rounded-md text-blue-600/70">
                                    <span class="text-[10px] font-black uppercase tracking-tighter">IN</span>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">LinkedIn</p>
                                    <p class="text-xs font-semibold text-gray-600 truncate">{{ $applicant->profile->linkedin ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Alamat & Domisili --}}
                        <div class="pt-4 border-t border-gray-100">
                            @php
                                $alamat = $applicant->profile->alamat;
                                $domisili = $applicant->profile->domisili;
                                $isDifferent = ($domisili && $alamat) && (strtolower(trim($alamat)) !== strtolower(trim($domisili)));
                            @endphp

                            <div class="grid grid-cols-1 {{ $isDifferent ? 'md:grid-cols-2' : '' }} gap-4">
                                
                                {{-- Kolom Alamat (KTP) --}}
                                <div class="flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-0.5">Alamat KTP</p>
                                        <p class="text-[11px] leading-relaxed text-gray-600 font-medium">
                                            {{ $alamat ?? 'Alamat belum diisi' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Kolom Domisili (Hanya muncul jika BERBEDA) --}}
                                @if($isDifferent)
                                <div class="flex items-start gap-2 border-t md:border-t-0 md:border-l border-gray-100 pt-3 md:pt-0 md:pl-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-0.5">Domisili Saat Ini</p>
                                        <p class="text-[11px] leading-relaxed text-gray-600 font-medium">
                                            {{ $domisili }}
                                        </p>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>

                    </div>
                    
                </div>
            </div>

            <div class="p-8 space-y-10">
                
                {{-- 2. INFORMASI PERSONAL & KESEHATAN --}}
                <section>
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        Informasi Personal & Fisik <div class="h-[1px] flex-1 bg-gray-100"></div>
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-8 gap-x-6">
                        {{-- Data Identitas --}}
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Jenis Kelamin</p>
                            <p class="text-sm font-semibold">{{ $applicant->profile->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">NIK (Sesuai KTP)</p>
                            <p class="text-sm font-bold text-gray-800 tracking-wider">{{ $applicant->profile->nik }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Agama</p>
                            <p class="text-sm font-semibold">{{ $applicant->profile->agama }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Status Nikah</p>
                            <p class="text-sm font-semibold">{{ $applicant->profile->status_nikah }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Tempat & Tanggal Lahir</p>
                            <p class="text-sm font-semibold">{{ $applicant->profile->tempat_lahir }}, {{ $applicant->profile->tanggal_lahir?->format('d M Y') }}</p>
                        </div>

                        {{-- Data Fisik --}}
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Tinggi / Berat</p>
                            <p class="text-sm font-semibold">{{ $applicant->profile->tinggi_badan }} cm / {{ $applicant->profile->berat_badan }} kg</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Ex-Karyawan</p>
                            @if($applicant->profile->ex_employee == 'Ya')
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-red-600 italic">Ya</span>
                                    <p class="text-[11px] text-gray-600">
                                        Perusahaan: <span class="font-bold">{{ $applicant->profile->ex_company_name ?? '-' }}</span> | 
                                        Jabatan: <span class="font-bold">{{ $applicant->profile->ex_last_position ?? '-' }}</span>
                                    </p>
                                </div>
                            @else
                                <p class="text-sm font-semibold text-gray-700 italic">Tidak</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Kepemilikan SIM</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($applicant->profile->jenis_sim ?? [] as $sim) 
                                    <span class="bg-gray-100 border border-gray-200 px-2 py-0.5 rounded text-[10px] font-bold text-gray-600 uppercase">SIM {{ $sim }}</span> 
                                @empty
                                    <span class="text-sm font-semibold text-gray-400">-</span>
                                @endforelse
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Perokok / Bertato</p>
                            <p class="text-sm font-semibold">{{ ucfirst($applicant->profile->perokok) }} / {{ ucfirst($applicant->profile->bertato) }} </p>
                        </div>
                        {{-- Riwayat Penyakit --}}
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Riwayat Penyakit</p>
                            <p class="text-sm font-medium text-gray-700 leading-relaxed italic">
                                {{ $applicant->profile->penyakit ?? 'Tidak ada riwayat penyakit serius' }}
                            </p>
                        </div>
                    </div>
                </section>
                
                {{-- 3. PENDIDIKAN FORMAL --}}
                <section>
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                        Pendidikan Formal <div class="h-[1px] flex-1 bg-gray-100"></div>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 uppercase font-bold border-b border-gray-100">
                                    <th class="px-4 py-2 border-r border-gray-100">Jenjang</th>
                                    <th class="px-4 py-2 border-r border-gray-100">Sekolah</th>
                                    <th class="px-4 py-2 border-r border-gray-100">Jurusan</th>
                                    <th class="px-4 py-2 border-r border-gray-100 text-center">Tahun Masuk</th>
                                    <th class="px-4 py-2 border-r border-gray-100 text-center">Tahun Lulus</th>
                                    <th class="px-4 py-2 text-center">IPK/Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($applicant->profile->pendidikan_formal ?? [] as $edu)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-4 py-3 font-bold text-gray-700">{{ $edu['jenjang'] ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $edu['sekolah'] ?? '-' }}</td>
                                    <td class="px-4 py-3 italic text-gray-600">{{ $edu['jurusan'] ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center text-gray-600">{{ $edu['tahun_masuk'] ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            // Logika: Jika tahun_lulus tidak ada ATAU is_current_edu bernilai true/on
                                            $masihSekolah = !isset($edu['tahun_lulus']) || 
                                                            (isset($edu['is_current_edu']) && ($edu['is_current_edu'] == 'on' || $edu['is_current_edu'] == '1'));
                                        @endphp

                                        @if($masihSekolah)
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[9px] font-bold uppercase tracking-tight">
                                                Masih Masa Pendidikan
                                            </span>
                                        @else
                                            <span class="font-semibold text-gray-700">{{ $edu['tahun_lulus'] }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-gray-800">{{ $edu['nilai'] ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-400 italic">Data pendidikan belum diisi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- 4. PENGALAMAN KERJA --}}
                <section>
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                        Pengalaman Kerja <div class="h-[1px] flex-1 bg-gray-100"></div>
                    </h3>
                    <div class="space-y-6">
                        @forelse($applicant->profile->pengalaman_kerja ?? [] as $job)
                        <div class="bg-slate-50 border border-gray-100 rounded-lg p-4">
                            <div class="flex justify-between items-start border-b border-gray-200 pb-2 mb-3">
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 uppercase">{{ $job['perusahaan'] ?? '-' }}</h4>
                                    <p class="text-xs text-red-600 font-bold uppercase">
                                        {{ $job['jabatan'] ?? '-' }} • 
                                        <span class="text-gray-400 font-medium italic uppercase">{{ $job['divisi'] ?? '-' }}</span>
                                    </p>
                                </div>
                                {{-- Periode dengan Logika Tanggal & Masih Bekerja --}}
                                <div class="text-right">
                                    <span class="text-[10px] font-bold bg-white px-2 py-1 rounded shadow-sm border border-gray-100">
                                        {{ isset($job['tanggal_masuk']) ? \Carbon\Carbon::parse($job['tanggal_masuk'])->format('M Y') : '?' }} 
                                        - 
                                        @if(isset($job['masih_bekerja']) && ($job['masih_bekerja'] == true || $job['masih_bekerja'] == 'on'))
                                            <span class="text-green-600">Sekarang</span>
                                        @else
                                            {{ isset($job['tanggal_keluar']) ? \Carbon\Carbon::parse($job['tanggal_keluar'])->format('M Y') : '?' }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-[11px]">
                                <div>
                                    <p class="text-gray-400 font-bold uppercase text-[9px]">Gaji Terakhir</p>
                                    <p class="font-semibold text-gray-700">{{ $job['gaji'] ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 font-bold uppercase text-[9px]">Fasilitas</p>
                                    <p class="font-semibold text-gray-700">{{ $job['fasilitas'] ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 font-bold uppercase text-[9px]">Referensi (Kontak)</p>
                                    <p class="font-semibold text-gray-700">{{ $job['kontak_referensi'] ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 font-bold uppercase text-[9px]">Alasan Berhenti</p>
                                    <p class="font-semibold text-gray-700">
                                        @if(isset($job['masih_bekerja']) && ($job['masih_bekerja'] == true || $job['masih_bekerja'] == 'on'))
                                            <span class="italic text-gray-400 italic">Masih Aktif Bekerja</span>
                                        @else
                                            {{ $job['alasan'] ?? '-' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-gray-400 italic text-center py-4">Belum ada pengalaman kerja yang dicantumkan.</p>
                        @endforelse
                    </div>
                </section>

                {{-- 5. KELUARGA (INTI & KANDUNG) --}}
                <section class="space-y-10">
                    @foreach(['inti' => 'Keluarga Inti (Istri/Suami/Anak)', 'kandung' => 'Keluarga Kandung (Orang Tua/Saudara)'] as $key => $title)
                    <div>
                        <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            {{ $title }} <div class="h-[1px] flex-1 bg-gray-100"></div>
                        </h3>
                        
                        <div class="overflow-x-auto border border-gray-100 rounded-lg">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 uppercase font-bold border-b border-gray-100">
                                        <th class="px-4 py-2 border-r border-gray-100">Nama Anggota</th>
                                        <th class="px-4 py-2 border-r border-gray-100">Hubungan</th>
                                        <th class="px-4 py-2 border-r border-gray-100">Tempat, Tanggal Lahir</th>
                                        <th class="px-4 py-2 border-r border-gray-100 text-center">Pendidikan</th>
                                        <th class="px-4 py-2 border-r border-gray-100">Pekerjaan</th>
                                        <th class="px-4 py-2 text-center">Kontak</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($applicant->profile->familyMembers->where('tipe', $key) as $fam)
                                        @php
                                            $isDeceased = str_contains($fam->nama ?? '', '+');
                                            $cleanName = trim(str_replace('+', '', $fam->nama ?? '-'));
                                            $age = $fam->tgl_lahir?->age;
                                        @endphp
                                        <tr class="hover:bg-gray-50/50">
                                            <td class="px-4 py-3 font-bold text-gray-700">
                                                {{ $isDeceased ? '(Alm/h) ' : '' }}{{ $cleanName }}
                                            </td>
                                            <td class="px-4 py-3 uppercase font-medium text-red-600 text-[10px]">
                                                {{ $fam->hubungan ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $fam->tempat_lahir ?? '-' }},
                                                <span class="text-gray-400">{{ $fam->tgl_lahir?->format('d/m/Y') ?? '-' }}</span>
                                                @if($age) <span class="font-bold text-gray-500 text-[10px]">({{ $age }} Th)</span> @endif
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-600 font-medium">
                                                {{ $fam->pendidikan ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $fam->pekerjaan ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-600">
                                                {{ $fam->hp ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-4 text-center text-gray-400 italic">Data tidak tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </section>

                {{-- 6. PREFERENSI KERJA & MINAT --}}
                <section class="mt-10">
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        Preferensi Kerja & Minat <div class="h-[1px] flex-1 bg-gray-100"></div>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Kolom Kiri: Gaji & Mobilisasi --}}
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Gaji yang Diharapkan</p>
                                    <p class="text-sm font-bold text-red-600">{{ $applicant->profile->expected_salary }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Fasilitas Diharapkan</p>
                                    <p class="text-sm font-semibold">{{ $applicant->profile->expected_facilities ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-2">
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Ready Dinas Luar Kota</p>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $applicant->profile->ready_dinas == 'Ya' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $applicant->profile->ready_dinas }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Ready Penempatan Luar Kota</p>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $applicant->profile->ready_placed_out == 'Ya' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $applicant->profile->ready_placed_out }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2">
                                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Referensi di Perusahaan Ini</p>
                                <p class="text-sm font-medium text-gray-700 italic">{{ $applicant->profile->company_reference ?? 'Tidak Ada' }}</p>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Urutan Minat (Tampilan Split 2 Kolom agar Fit) --}}
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col justify-center">
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-3 tracking-widest text-center">Prioritas Minat Bekerja</p>
                            
                            <div class="grid grid-cols-2 gap-x-4">
                                @php 
                                    $minats = $applicant->profile->minat ?? [];
                                    $total = count($minats);
                                    
                                    // Inisialisasi awal agar tidak error
                                    $half = 0;
                                    $chunks = [];

                                    // Hanya jalankan logika jika ada data
                                    if ($total > 0) {
                                        // Menentukan titik potong (jika 13, maka kiri dapat 7, kanan dapat 6)
                                        $half = ceil($total / 2); 
                                        $chunks = array_chunk($minats, $half);
                                    }
                                @endphp
                                
                                @forelse($chunks as $chunkIndex => $chunk)
                                    <div class="space-y-1.5">
                                        @foreach($chunk as $itemIndex => $item)
                                            @php 
                                                // Menghitung nomor urut asli (1, 2, 3... dst)
                                                $originalIndex = ($chunkIndex * $half) + $itemIndex + 1; 
                                            @endphp
                                            <div class="flex items-center bg-white px-2 py-1 rounded-md border border-gray-100 shadow-sm">
                                                <span class="w-4 h-4 flex items-center justify-center bg-red-600 text-white rounded-full text-[8px] font-black mr-2 flex-shrink-0">
                                                    {{ $originalIndex }}
                                                </span>
                                                <span class="text-[9px] font-bold text-gray-700 uppercase tracking-tighter truncate">
                                                    {{ $item == 'R & D' ? 'Research & Development' : $item }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @empty
                                    {{-- Ini akan muncul jika $chunks kosong, desain tetap aman --}}
                                    <div class="col-span-2 text-[10px] text-gray-400 italic text-center py-2">Data minat belum diisi.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 7. PENDIDIKAN INFORMAL & DOKUMEN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <section>
                        <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">Pelatihan / Kursus</h3>
                        <div class="space-y-2">
                            @foreach($applicant->profile->pendidikan_informal ?? [] as $info)
                                <div class="p-3 bg-white border border-gray-100 rounded-lg flex justify-between items-center">
                                    <div>
                                        <p class="text-xs font-bold text-gray-800 uppercase">{{ $info['kursus'] }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $info['penyelenggara'] }}</p>
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-400 italic">{{ $info['tahun'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section>
                        <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">Lampiran Berkas</h3>
                        <div class="grid grid-cols-3 gap-2">
                            @php $docLabels = ['ktp' => 'KTP', 'cv' => 'CV', 'ijazah' => 'Ijazah', 'npwp' => 'NPWP', 'sim' => 'SIM', 'bpjs_kes' => 'BPJS', 'lain' => 'Lainnya']; @endphp
                            @foreach($docLabels as $key => $label)
                                @if($docsByType->get($key))
                                    <a href="{{ asset('storage/' . $docsByType->get($key)->file_path) }}" target="_blank" class="p-2 border border-dashed border-gray-200 rounded text-center text-[9px] font-bold text-gray-500 hover:bg-red-50 hover:text-red-600 uppercase">
                                        {{ $label }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="bg-gray-50 p-6 border-t border-gray-200 no-print">
                <p class="text-[10px] text-center text-gray-400 uppercase font-medium tracking-widest italic">Dokumen ini dihasilkan secara digital oleh Sistem Rekrutmen - {{ date('Y') }}</p>
            </div>
        </div>
        
        {{-- Back Button --}}
        <div class="mt-8 flex justify-center no-print">
            <a href="{{ route('applicant.dashboard') }}" class="group inline-flex items-center px-6 py-2 bg-white border border-gray-200 text-gray-500 font-bold rounded-full hover:text-red-600 hover:border-red-100 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; padding: 0 !important; }
            .shadow-sm, .shadow-2xl { box-shadow: none !important; }
            .max-w-5xl { max-width: 100% !important; width: 100% !important; margin: 0 !important; }
            section { page-break-inside: avoid; }
        }
    </style>
</x-guest-layout>