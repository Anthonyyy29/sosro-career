<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- SEO (start) --}}
    <title>{{ config('app.name', 'Sosro Career') }}</title>
    <meta name="description" content="Temukan lowongan kerja terbaru di PT Sinar Sosro Gunung Slamat.">
    <meta name="keywords" content="sosro, teh botol sosro, sosro career, karir sosro, lowongan kerja sosro, rekrutmen pt sinar sosro gunung slamat, 
                loker pabrik teh, gunung slamat career, sosro lowongan, sosro rekrutmen, karir pabrik teh, loker teh sosro, sosro job, sosro karir, 
                gunung slamat lowongan, pabrik teh sosro rekrutmen, sosro hiring, sosro recruitment, gunung slamat career, fmcg karir, fmcg sosro, 
                lowongan fmcg indonesia, sosro fmcg job, karir fmcg teh, loker fmcg pabrik, sosro fmcg recruitment">
    <meta property="og:title" content="Karir PT Sinar Sosro Gunung Slamat">
    <meta property="og:description" content="Bergabunglah bersama PT Sinar Sosro Gunung Slamat. Cek lowongan aktif di karir.sosro.com">
    <meta property="og:url" content="https://karir.sosro.com">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('assets/images/logo sosro.webp') }}">
    {{-- SEO (end) --}}
    <link rel="icon" href="{{ asset('assets/images/logo sosro.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>/* SOSRO Brand Loader */
        .loader {
        display: flex;
        align-items: center;
        }

        .bar {
        display: inline-block;
        width: 4px;
        height: 22px;
        background-color: #B11116; /* MERAH BRAND */
        border-radius: 10px;
        animation: sosro-loading 1s linear infinite;
        }

        .bar:nth-child(2) {
        height: 32px;
        margin: 0 6px;
        animation-delay: .25s;
        }

        .bar:nth-child(3) {
        animation-delay: .5s;
        }

        @keyframes sosro-loading {
            20% {
                background-color: #7a0c22; /* darker red highlight */
                transform: scaleY(1.5);
            }
            40% {
                transform: scaleY(1);
            }
        }

        .job-card ul {
            list-style-type: disc !important;
            margin-left: 1.25rem !important;
        }
        .job-card ol {
            list-style-type: decimal !important;
            margin-left: 1.25rem !important;
        }
        .job-card strong {
            font-weight: 700 !important;
        }
        .job-card em {
            font-style: italic !important;
        }
        .job-card u {
            text-decoration: underline !important;
        }
    </style>

    <style>
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
        }

        /* Menghilangkan panah di IE/Edge */
        select::-ms-expand {
            display: none !important;
        }
    </style>

    <x-turnstile.scripts />
</head>
<body class="font-sans antialiased bg-gray-50">

    <!-- PAGE LOADER -->
    <div id="page-loader"
        class="fixed inset-0 z-[9999] flex items-center justify-center 
                bg-white/70 backdrop-blur-md opacity-100 transition-opacity duration-[700ms]">

        <div class="loader">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </div>


    {{-- NAVBAR --}}
    {{-- <nav class="fixed top-0 left-0 w-full bg-[#B11116] text-white shadow-lg z-50"> --}}
    <nav id="main-navbar" class="fixed top-0 left-0 w-full text-white z-50 transition-all duration-500 ease-in-out">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('assets/images/SGS Logo-Putih.webp') }}" 
                    alt="Logo Sinar Sosro Gunung Slamat" 
                    class="h-11 w-auto">
            </div>

            {{-- DESKTOP MENU --}}
            <div class="hidden md:flex space-x-8">

                {{-- BERANDA --}}
                {{-- <a href="{{ route('beranda') }}" class="relative group font-medium {{ request()->routeIs('beranda') ? 'text-white' : 'text-gray-100' }}">
                    
                    Beranda

                    <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300
                        {{ request()->routeIs('beranda') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                    </span>
                </a> --}}

                {{-- TENTANG --}}
                {{-- <a href="{{ route('tentang') }}" class="relative group font-medium {{ request()->routeIs('tentang') ? 'text-white' : 'text-gray-100' }}">
                    
                    Tentang

                    <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300
                        {{ request()->routeIs('tentang') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                    </span>
                </a> --}}

                {{-- PROGRAM --}}
                {{-- <a href="{{ route('program') }}" class="relative group font-medium {{ request()->routeIs('program') ? 'text-white' : 'text-gray-100' }}">
                    
                    Program

                    <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300
                        {{ request()->routeIs('program') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                    </span>
                </a> --}}

                {{-- LOWONGAN --}}
                {{--                 
                <a href="{{ route('lowongan') }}" class="relative group font-medium {{ request()->routeIs('lowongan') ? 'text-white' : 'text-gray-100' }}">
                    
                    Lowongan

                    <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300
                        {{ request()->routeIs('lowongan') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                    </span>
                </a> --}}

                {{-- KONTAK --}}
                {{-- <a href="{{ route('kontak') }}" class="relative group font-medium {{ request()->routeIs('kontak') ? 'text-white' : 'text-gray-100' }}">
                    
                    Kontak

                    <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300
                        {{ request()->routeIs('kontak') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                    </span>
                </a> --}}
                
                {{-- 
                Semua konten di disable untuk soft launch.
                --}}

            </div>

            {{-- AKSI DAN TRANSLATE --}}
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    {{-- KALAU SUDAH LOGIN: USER PROFILE DROPDOWN --}}
                    <div class="relative" x-data="{ open: false }">
                        {{-- Trigger: Nama/Email di Kiri, Avatar di Kanan --}}
                        <button @click="open = !open" @click.away="open = false" 
                            class="flex items-center space-x-3 bg-black/20 hover:bg-black/30 backdrop-blur-md border border-white/10 p-1.5 pl-4 pr-1.5 rounded-full transition-all duration-300 focus:outline-none group shadow-sm">
                            
                            {{-- Info User (Kiri) --}}
                            <div class="text-right hidden sm:block">
                                <p class="text-[11px] font-bold text-white tracking-wide leading-none mb-1 drop-shadow-sm">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-[9px] font-medium text-gray-200/80 leading-none truncate max-w-[130px] drop-shadow-sm">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>

                            {{-- Avatar Bulat (Kanan) --}}
                            <div class="h-8 w-8 rounded-full bg-red-600 text-white flex items-center justify-center font-bold shadow-md border border-white/20 transition group-hover:scale-105 group-active:scale-95">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            style="display: none;"
                            class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-2xl py-2 z-[70] border border-gray-100 overflow-hidden">
                            
                            {{-- Header Dropdown untuk Mobile (Karena info di trigger disembunyikan di HP) --}}
                            <div class="px-4 py-3 border-b border-gray-50 mb-1 sm:hidden">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-red-50 transition">
                                <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center mr-3 group-hover:bg-gray-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 text-[#B11116]">
                                        <path fill-rule="evenodd" d="M4.25 2A2.25 2.25 0 0 0 2 4.25v2.5A2.25 2.25 0 0 0 4.25 9h2.5A2.25 2.25 0 0 0 9 6.75v-2.5A2.25 2.25 0 0 0 6.75 2h-2.5Zm0 9A2.25 2.25 0 0 0 2 13.25v2.5A2.25 2.25 0 0 0 4.25 18h2.5A2.25 2.25 0 0 0 9 15.75v-2.5A2.25 2.25 0 0 0 6.75 11h-2.5Zm9-9A2.25 2.25 0 0 0 11 4.25v2.5A2.25 2.25 0 0 0 13.25 9h2.5A2.25 2.25 0 0 0 18 6.75v-2.5A2.25 2.25 0 0 0 15.75 2h-2.5Zm0 9A2.25 2.25 0 0 0 11 13.25v2.5A2.25 2.25 0 0 0 13.25 18h2.5A2.25 2.25 0 0 0 18 15.75v-2.5A2.25 2.25 0 0 0 15.75 11h-2.5Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                Dashboard Pelamar
                            </a>
{{-- 
                            <a href="{{ route('profile.edit') }}" class="group flex items-center px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-red-50 transition">
                                <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center mr-3 group-hover:bg-gray-200 transition">
                                    <i class="fa-solid fa-user-gear text-gray-600 text-xs"></i>
                                </div>
                                Akun Saya
                            </a> --}}
                            
                            <hr class="my-1 border-gray-50">
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="group flex items-center w-full px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition text-left">
                                    <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center mr-3 group-hover:bg-red-100 transition">
                                        <i class="fa-solid fa-power-off text-red-600 text-xs"></i>
                                    </div>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- Jika belum login --}}
                    <a href="{{ route('login') }}" 
                    class="px-6 py-2 bg-white text-red-700 font-bold rounded-full hover:bg-gray-100 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                        Masuk
                    </a>
                @endauth

                {{-- LANGUAGE SWITCHER (DESKTOP) --}}
                <div class="relative inline-block text-left" x-data="{ open: false, lang: 'ID', flag: 'id' }">
                    <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center space-x-2 py-2 focus:outline-none">
                        
                        {{-- Bendera Bulat --}}
                        <div class="w-6 h-6 rounded-full overflow-hidden border border-white/50 shadow-sm flex-shrink-0">
                            <img :src="`https://flagcdn.com/w40/${flag}.png`" 
                                :alt="lang" 
                                class="w-full h-full object-cover">
                        </div>

                        {{-- Label ID/EN --}}
                        <span class="text-white font-bold text-sm tracking-tight" x-text="lang">ID</span>

                        {{-- Icon Dropdown --}}
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 text-white transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        style="display: none;"
                        class="absolute right-0 mt-2 w-32 bg-white rounded-2xl shadow-2xl py-2 z-[70] border border-gray-100 overflow-hidden">
                        
                        {{-- Pilihan Indonesia --}}
                        <button @click="lang = 'ID'; flag = 'id'; open = false; changeLanguage('id')" 
                            class="flex items-center space-x-3 px-4 py-2 w-full text-left hover:bg-red-50 transition">
                            <img src="https://flagcdn.com/w40/id.png" class="w-5 h-5 rounded-full object-cover" alt="ID">
                            <span class="text-gray-800 text-sm font-semibold">Indonesia</span>
                        </button>

                        {{-- Pilihan English --}}
                        <button @click="lang = 'EN'; flag = 'gb'; open = false; changeLanguage('en')" 
                            class="flex items-center space-x-3 px-4 py-2 w-full text-left hover:bg-red-50 transition border-t border-gray-50">
                            <img src="https://flagcdn.com/w40/gb.png" class="w-5 h-5 rounded-full object-cover" alt="EN">
                            <span class="text-gray-800 text-sm font-semibold">English</span>
                        </button>
                    </div>
                </div>
                
            </div>

            {{-- MOBILE MENU BUTTON --}}
            <div class="md:hidden flex items-center">
                <button id="menu-toggle" class="focus:outline-none text-white">
                    <div id="menu-icon-container">
                        {{-- Default: Hamburger --}}
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>

        {{-- MOBILE MENU --}}
        <div id="mobile-menu" class="hidden md:hidden bg-[#B11116]">
            {{-- <a href="{{ route('beranda') }}" class="block px-4 py-2 text-white hover:bg-red-800">Beranda</a>
            <a href="{{ route('tentang') }}" class="block px-4 py-2 text-white hover:bg-red-800">Tentang</a>
            <a href="{{ route('program') }}" class="block px-4 py-2 text-white hover:bg-red-800">Program</a>
            <a href="{{ route('lowongan') }}" class="block px-4 py-2 text-white hover:bg-red-800">Lowongan</a>
            <a href="{{ route('kontak') }}" class="block px-4 py-2 text-white hover:bg-red-800">Kontak</a> --}}
            {{-- Disable Mobile Menu untuk soft launch --}}

            {{-- TRANSLATE BUTTON --}}
            <div class="px-4 py-4" x-data="{ currentLang: '{{ strtoupper(App::getLocale()) }}' }">
                {{-- <p class="text-xs text-red-200 font-medium mb-3 tracking-wider opasity-90">Pilih Bahasa / Select Language</p> --}}
                <div class="grid grid-cols-2 gap-3">
                    {{-- Tombol Indonesia --}}
                    <button @click="changeLanguage('id'); currentLang = 'ID'" 
                        :class="currentLang === 'ID' ? 'bg-red-200 text-red-700' : 'bg-[#B11116] text-white border border-red-400'"
                        class="flex items-center justify-center space-x-2 py-3 rounded-xl font-bold transition-all active:scale-95 hover:opacity-90">
                        <img src="https://flagcdn.com/w40/id.png" class="w-5 h-5 rounded-full object-cover shadow-sm" alt="ID">
                        <span>ID</span>
                    </button>

                    {{-- Tombol English --}}
                    <button @click="changeLanguage('en'); currentLang = 'EN'" 
                        :class="currentLang === 'EN' ? 'bg-red-200 text-red-700' : 'bg-[#B11116] text-white border border-red-400'"
                        class="flex items-center justify-center space-x-2 py-3 rounded-xl font-bold transition-all active:scale-95 hover:opacity-90">
                        <img src="https://flagcdn.com/w40/gb.png" class="w-5 h-5 rounded-full object-cover shadow-sm" alt="EN">
                        <span>EN</span>
                    </button>
                </div>
            </div>

            {{-- AUTH HANDLING MOBILE --}}
            <div class="border-t border-red-400 mt-2 pt-2 pb-4">
                @auth
                    <div class="px-4 py-3 flex items-center space-x-3 mb-2">
                        <div class="h-10 w-10 rounded-full bg-white text-red-700 flex items-center justify-center font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-white font-bold leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-red-200 text-xs">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-3 text-white hover:bg-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M4.25 2A2.25 2.25 0 0 0 2 4.25v2.5A2.25 2.25 0 0 0 4.25 9h2.5A2.25 2.25 0 0 0 9 6.75v-2.5A2.25 2.25 0 0 0 6.75 2h-2.5Zm0 9A2.25 2.25 0 0 0 2 13.25v2.5A2.25 2.25 0 0 0 4.25 18h2.5A2.25 2.25 0 0 0 9 15.75v-2.5A2.25 2.25 0 0 0 6.75 11h-2.5Zm9-9A2.25 2.25 0 0 0 11 4.25v2.5A2.25 2.25 0 0 0 13.25 9h2.5A2.25 2.25 0 0 0 18 6.75v-2.5A2.25 2.25 0 0 0 15.75 2h-2.5Zm0 9A2.25 2.25 0 0 0 11 13.25v2.5A2.25 2.25 0 0 0 13.25 18h2.5A2.25 2.25 0 0 0 18 15.75v-2.5A2.25 2.25 0 0 0 15.75 11h-2.5Z" clip-rule="evenodd" />
                        </svg>
                        Dashboard Pelamar
                    </a>

                    {{-- <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-3 text-white hover:bg-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd" />
                        </svg>
                        Akun Pelamar
                    </a> --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="block w-full text-left px-4 py-3 text-white hover:bg-red-900 font-medium">
                            <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar Aplikasi
                        </button>
                    </form>
                @else
                    <div class="px-4 py-2">
                        <a href="{{ route('login') }}" 
                        class="block w-full text-center px-4 py-3 bg-white text-red-700 font-bold rounded-lg shadow-inner transition-all active:scale-95 hover:bg-red-100">
                            Masuk ke Akun
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
    
    {{-- SCRIPT TOGGLE MOBILE MENU --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('menu-toggle');
            const menu = document.getElementById('mobile-menu');
            const iconContainer = document.getElementById('menu-icon-container');

            const hamburgerIcon = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>`;
            const closeIcon = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>`;

            if (!btn || !menu) return;

            btn.addEventListener('click', () => {
                const isHidden = menu.classList.toggle('hidden');
                // Jika menu tersembunyi (hidden), pakai hamburger. Jika tidak, pakai silang.
                iconContainer.innerHTML = isHidden ? hamburgerIcon : closeIcon;
            });
        });
    </script>

    {{-- CONTENT --}}
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <footer class="bg-[#B11116] text-white pt-16 pb-8 px-6 md:px-16">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-12 gap-10">
                
                {{-- Kolom 1: Nama & Alamat (Lebar) --}}
                <div class="lg:col-span-4">
                    <h3 class="text-2xl font-bold mb-4 uppercase tracking-tighter">PT SINAR SOSRO GUNUNG SLAMAT</h3>
                    <p class="text-[15px] leading-relaxed">
                        <span class="font-bold">Kantor Pusat:</span> Graha Rekso Lantai 8 - 10. Jalan Bulevard Artha Gading No.Kav.A1 RT/RW 18/08 Kelapa Gading, Jakarta Utara Daerah Khusus Ibukota Jakarta 14240
                    </p>
                    <p class="text-[15px] mt-4">
                        <span class="font-bold">Telepon:</span> (021) 4585 6268
                    </p>
                </div>

                {{-- Kolom 2: Kontak & Jam Operasional --}}
                <div class="lg:col-span-3">
                    <div class="mb-6">
                        <p class="text-[15px]">
                            <span class="font-bold">Email:</span> 
                            <a href="mailto:recruitment.hrd@sosro.com" class="hover:underline">recruitment.hrd@sosro.com</a> / 
                            <a href="mailto:recruitment.ho@sosro.com" class="hover:underline">recruitment.ho@sosro.com</a>
                        </p>
                    </div>
                    <div>
                        <p class="text-[15px] leading-tight">
                            <span class="font-bold">Jam Operasional:</span><br>
                            Senin - Jumat: 08:00 17:00<br>
                            Sabtu- Minggu: Tutup
                        </p>
                    </div>
                </div>

                {{-- Kolom 3: Media Sosial --}}
                <div class="lg:col-span-2">
                    <h4 class="text-xl font-bold mb-4">Media Sosial</h4>
                    <div class="flex space-x-4">
                        <a href="https://www.instagram.com/sosrocareer/" class="hover:opacity-80 transition text-2xl">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/company/pt-sinar-sosro-gunung-slamat/" class="hover:opacity-80 transition text-2xl">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="https://www.tiktok.com/@lifeatsosro" class="hover:opacity-80 transition text-2xl">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>

                {{-- Kolom 4: Karier & Info (Digabung agar ringkas seperti gambar) --}}
                <div class="lg:col-span-3 grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-xl font-bold mb-4">Karier</h4>
                        <ul class="space-y-2 text-[15px]">
                            <li><a href="{{ route('lowongan') }}" class="hover:underline">Lowongan</a></li>
                            <li><a href="#" class="hover:underline">Budaya Kerja</a></li>
                            <li><a href="#" class="hover:underline">Pelatihan</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold mb-4">Info</h4>
                        <ul class="space-y-2 text-[15px]">
                            <li><a href="#" class="hover:underline">Tentang Kami</a></li>
                            <li><a href="#" class="hover:underline">Kebijakan Privasi</a></li>
                            <li><a href="#" class="hover:underline">FAQ</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Copyright Section --}}
        <div class="pt-8 mt-4">
            <div class="text-center text-[16px] text-red-800 font-medium bg-white py-4 -mx-6 md:-mx-16 -mb-8">
                <p>© Copyright {{ date('Y') }}. <span class="font-bold">PT Sinar Sosro Gunung Slamat.</span> All Rights Reserved</p>
            </div>
        </div>
    </footer>
    
    {{-- FAKE LOADER SCRIPT --}}
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const loader = document.getElementById("page-loader");

        // fade-out super smooth setelah halaman beres
        setTimeout(() => {
            loader.style.opacity = "0";
            setTimeout(() => loader.style.display = "none", 700);
        }, 400);

        // intercept semua link untuk fade-in smooth
        document.querySelectorAll("a[href]").forEach(link => {
            link.addEventListener("click", e => {
                const url = link.getAttribute("href");

                if (
                    !url ||
                    url.startsWith("#") ||
                    link.target === "_blank" ||
                    link.closest("form")
                ) return;

                e.preventDefault();

                // show loader — smooth, no flash
                loader.style.display = "flex";
                setTimeout(() => loader.style.opacity = "1", 10);

                setTimeout(() => {
                    window.location.href = url;
                }, 100);
            });
        });
    });
    </script>

    {{-- Turnstile Js --}}
    <script>
        document.addEventListener('turnstile-error', function() {
            // Jika ada error, otomatis reset widget
            turnstile.reset();
        });
    </script>
    <script>
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('main-navbar');
            if (window.scrollY > 50) {
                // State saat di-scroll (Solid)
                nav.classList.add('bg-[#B11116]', 'shadow-lg', 'py-0');
                nav.classList.remove('py-2', 'bg-transparent');
            } else {
                // State awal (Transparent)
                nav.classList.remove('bg-[#B11116]', 'shadow-lg', 'py-0');
                nav.classList.add('py-2', 'bg-transparent');
            }
        });
    </script>

    {{-- carousel beranda --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- FITUR TRANSLATE --}}
    <div id="google_translate_element" style="display:none;"></div>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'en,id',
                autoDisplay: false
            }, 'google_translate_element');
        }

        function changeLanguage(langCode) {
            const select = document.querySelector('#google_translate_element select');
            if (select) {
                select.value = langCode;
                select.dispatchEvent(new Event('change'));
            }
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <style>
        /* Paksa sembunyikan semua UI bawaan Google yang merusak layout */
        .goog-te-banner-frame, 
        .goog-te-gadget, 
        .goog-te-banner,
        #goog-gt-tt,
        .goog-te-balloon-frame {
            display: none !important;
            visibility: hidden !important;
        }
        body {
            top: 0 !important;
        }
        .skiptranslate {
            display: none !important;
        }
    </style>
</body>
</html>
