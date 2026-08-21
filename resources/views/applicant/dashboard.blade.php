<x-guest-layout>
    <div class="py-8 md:py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-gray-800 tracking-tight">
                    Halo, <span class="text-red-600">{{ Auth::user()->name }}!</span>
                </h2>
                <p class="text-gray-500 mt-1 font-medium">Selamat datang di <b>Dashboard Pelamar</b>, Pantau progres lamaran Anda di sini.</p>
            </div>
            
            {{-- Status Badge --}}
            <div class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 rounded-2xl shadow-sm group hover:border-red-50 transition-colors">
                <div class="relative flex h-3 w-3 mr-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $applicant && $applicant->profile_completed ? 'bg-green-400' : 'bg-amber-400' }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 {{ $applicant && $applicant->profile_completed ? 'bg-green-500' : 'bg-amber-500' }}"></span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase tracking-widest font-black text-gray-400 leading-none mb-1">Status Profil</span>
                    <span class="text-sm font-bold text-gray-700 leading-none">
                        {{ $applicant && $applicant->profile_completed ? 'Lengkap & Terverifikasi' : 'Perlu Dilengkapi' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Modal dokumen: muncul selama ada lamaran accepted tapi dokumen belum lengkap.
             Sengaja TIDAK bisa ditutup (tidak ada tombol X / "nanti saja" / klik luar) --
             satu-satunya jalan keluar adalah mengisi dokumen. Kondisinya dibaca langsung
             dari DB, jadi tetap muncul tiap kali dashboard dibuka sampai dokumen lengkap. --}}
        @if($applicant && $applicant->needsDocumentSubmission())
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm">
                <div class="bg-white rounded-[2rem] shadow-2xl max-w-md w-full p-8 text-center">
                    <div class="w-16 h-16 mx-auto bg-green-50 text-green-600 rounded-full flex items-center justify-center mb-5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-2">Selamat, lamaran Anda diterima!</h3>
                    <p class="text-gray-500 text-sm mb-6">
                        Untuk melanjutkan proses rekrutmen, Anda wajib melengkapi dokumen terlebih dahulu.
                    </p>

                    <a href="{{ route('applicant.documents.edit') }}"
                       class="block w-full py-4 bg-red-600 rounded-2xl text-white font-bold shadow-lg shadow-red-200 hover:bg-red-700 transition-all active:scale-95">
                        Lengkapi Dokumen Sekarang
                    </a>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main Content (Left) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Steps & Progress Card --}}
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-50 overflow-hidden relative group">
                    <div class="absolute -right-20 -top-20 w-60 h-60 bg-red-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                    
                    <div class="relative">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Langkah Persiapan</h3>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-red-600 text-white rounded-lg flex items-center justify-center font-bold text-sm">1</div>
                                <div class="ml-4">
                                    <p class="text-gray-800 font-bold">Lengkapi Profil Biodata</p>
                                    <p class="text-gray-500 text-sm">Pastikan data pribadi dan riwayat pendidikan sudah benar.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-red-600 text-white rounded-lg flex items-center justify-center font-bold text-sm">2</div>
                                <div class="ml-4">
                                    <p class="text-gray-800 font-bold">Unggah Dokumen Pendukung</p>
                                    <p class="text-gray-500 text-sm">Siapkan CV, Ijazah, dan berkas lainnya dalam format PDF.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    
                    {{-- 1. Profile Link --}}
                    @php
                        $profileRoute = ($applicant && $applicant->profile) ? route('applicant.profile.show') : route('applicant.profile.create');
                    @endphp
                    <a href="{{ $profileRoute }}"
                    class="group flex items-center p-5 bg-white border border-gray-200 rounded-full shadow-lg hover:shadow-md hover:border-blue-400 transition-all duration-200 active:scale-95">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <span class="block font-bold text-gray-800">Profil Saya</span>
                            <span class="block text-xs text-gray-400">Lengkapi biodata</span>
                        </div>
                    </a>

                    {{-- 2. Jobs Link (Conditional) --}}
                    @if($applicant && $applicant->profile_completed)
                        <a href="{{ route('lowongan') }}" 
                        class="group flex items-center p-5 bg-red-600 rounded-full shadow-lg shadow-red-200 hover:bg-red-700 transition-all duration-200 active:scale-95">
                            <div class="w-12 h-12 bg-white/20 text-white rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <span class="block font-bold text-white">Cari Lowongan</span>
                                <span class="block text-xs text-red-100">Lihat posisi aktif</span>
                            </div>
                        </a>
                    @else
                        <div class="flex items-center p-5 bg-gray-50 border border-gray-200 rounded-full opacity-60 cursor-not-allowed">
                            <div class="w-12 h-12 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <span class="block font-bold text-gray-400">Lowongan</span>
                                <span class="block text-[10px] text-gray-400 uppercase font-black">Terkunci</span>
                            </div>
                        </div>
                    @endif
                    
                    {{-- 3. Lamaran Saya (Conditional) --}}
                    @if($applicant && $applicant->profile_completed)
                        <a href="{{ route('applicant.applications.index') }}" 
                        class="group flex items-center p-5 bg-white border border-gray-200 rounded-full shadow-lg hover:shadow-md hover:border-green-400 transition-all duration-200 active:scale-95">
                            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                            </div>
                            <div class="ml-4">
                                <span class="block font-bold text-gray-800">Lamaran Saya</span>
                                <span class="block text-xs text-gray-400">Status rekrutmen</span>
                            </div>
                        </a>
                    @else
                        <div class="flex items-center p-5 bg-gray-50 border border-gray-200 rounded-full opacity-60 cursor-not-allowed">
                            <div class="w-12 h-12 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <span class="block font-bold text-gray-400">Lamaran Saya</span>
                                <span class="block text-[10px] text-gray-400 uppercase font-black">Terkunci</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar (Right) --}}
            <div class="space-y-6">
                <div class="bg-gray-900 p-6 rounded-[2rem] text-white overflow-hidden relative shadow-2xl shadow-gray-200">
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-red-600 rounded-full blur-[60px] opacity-50"></div>
                    
                    <h4 class="text-lg font-bold mb-2 relative">Butuh Bantuan?</h4>
                    <p class="text-gray-400 text-sm mb-4 relative">Jika mengalami kendala teknis saat pendaftaran, hubungi kami.</p>
                    {{-- <a href="{{ route('kontak') }}" class="inline-flex items-center text-red-400 font-bold text-sm hover:text-red-300 transition-colors">
                        Hubungi Kontak Kami
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a> --}}
                    {{-- Soft Launching: sementara halaman beranda diarahkan ke halaman lowongan --}}
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>