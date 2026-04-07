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
        background-color: #B51233; /* MERAH BRAND */
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
    {{-- <nav class="fixed top-0 left-0 w-full bg-[#B51233] text-white shadow-lg z-50"> --}}
    <nav id="main-navbar" class="fixed top-0 left-0 w-full text-white z-50 transition-all duration-500 ease-in-out">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('assets/images/SGS Logo-Putih.webp') }}" 
                    alt="Logo Sinar Sosro Gunung Slamat" 
                    class="h-11 w-auto">
            </div>

            {{-- DESKTOP MENU --}}
            <div class="hidden md:flex space-x-8">

                {{-- BERANDA --}}
                <a href="{{ route('beranda') }}" class="relative group font-medium {{ request()->routeIs('beranda') ? 'text-white' : 'text-gray-100' }}">
                    
                    Beranda

                    <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300
                        {{ request()->routeIs('beranda') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                    </span>
                </a>

                {{-- TENTANG --}}
                <a href="{{ route('tentang') }}" class="relative group font-medium {{ request()->routeIs('tentang') ? 'text-white' : 'text-gray-100' }}">
                    
                    Tentang

                    <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300
                        {{ request()->routeIs('tentang') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                    </span>
                </a>

                {{-- PROGRAM --}}
                <a href="{{ route('program') }}" class="relative group font-medium {{ request()->routeIs('program') ? 'text-white' : 'text-gray-100' }}">
                    
                    Program

                    <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300
                        {{ request()->routeIs('program') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                    </span>
                </a>

                {{-- LOWONGAN --}}
                <a href="{{ route('lowongan') }}" class="relative group font-medium {{ request()->routeIs('lowongan') ? 'text-white' : 'text-gray-100' }}">
                    
                    Lowongan

                    <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300
                        {{ request()->routeIs('lowongan') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                    </span>
                </a>

                {{-- KONTAK --}}
                <a href="{{ route('kontak') }}" class="relative group font-medium {{ request()->routeIs('kontak') ? 'text-white' : 'text-gray-100' }}">
                    
                    Kontak

                    <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300
                        {{ request()->routeIs('kontak') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                    </span>
                </a>

            </div>

            {{-- AKSI DAN TRANSLATE --}}
            <div class="hidden md:flex items-center space-x-4">
                {{-- fitur ganti bahasa --}}
                <select class="bg-red-700 border border-red-400 rounded-lg px-2 py-1 text-sm text-white focus:ring-2 focus:ring-white outline-none">
                    <option>ID</option>
                    <option>EN</option>
                </select>

                @auth
                    {{-- Jika sudah login: Tampilkan Button Dashboard & Avatar Mini --}}
                    <div class="flex items-center space-x-3 bg-red-800/50 p-1.5 rounded-full pl-4 border border-red-400/30">
                        <a href="{{ route('dashboard') }}" 
                        class="text-sm font-bold tracking-wide uppercase hover:text-gray-200 transition">
                            Dashboard
                        </a>
                        
                        {{-- Dropdown Sederhana (Menggunakan Alpine.js bawaan Laravel) --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-white text-red-700 flex items-center justify-center font-bold border-2 border-white shadow-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>

                            <div x-show="open" @click.away="open = false" 
                                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-[60] text-gray-800 border border-gray-100">
                                <div class="px-4 py-2 border-b border-gray-50 mb-1">
                                    <p class="text-xs text-gray-400">Masuk sebagai:</p>
                                    <p class="text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 flex items-center">
                                    <i class="fa-regular fa-user mr-2 w-4"></i> Profil Akun Saya
                                </a>
                                <hr class="my-1 border-gray-50">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold flex items-center">
                                        <i class="fa-solid fa-arrow-right-from-bracket mr-2 w-4"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Jika belum login --}}
                    <a href="{{ route('login') }}" 
                    class="px-6 py-2 bg-white text-red-700 font-bold rounded-full hover:bg-gray-100 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                        Masuk
                    </a>
                @endauth
            </div>

            {{-- MOBILE MENU BUTTON --}}
            <div class="md:hidden flex items-center">
                <button id="menu-toggle" class="focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- MOBILE MENU --}}
        <div id="mobile-menu" class="hidden md:hidden bg-[#B51233]">
            <a href="{{ route('beranda') }}" class="block px-4 py-2 text-white hover:bg-red-800">Beranda</a>
            <a href="{{ route('tentang') }}" class="block px-4 py-2 text-white hover:bg-red-800">Tentang</a>
            <a href="{{ route('program') }}" class="block px-4 py-2 text-white hover:bg-red-800">Program</a>
            <a href="{{ route('lowongan') }}" class="block px-4 py-2 text-white hover:bg-red-800">Lowongan</a>
            <a href="{{ route('kontak') }}" class="block px-4 py-2 text-white hover:bg-red-800">Kontak</a>

            {{-- Bahasa --}}
            <div class="px-4 py-2">
                <select class="w-full bg-red-700 border border-white rounded px-2 py-1 text-sm text-white">
                    <option>ID</option>
                    <option>EN</option>
                </select>
            </div>

            {{-- AUTH HANDLING --}}
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
                    <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-white bg-red-800 font-bold">
                        <i class="fa-solid fa-gauge-high mr-2"></i> Dashboard Utama
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-white hover:bg-red-800">
                        <i class="fa-regular fa-user mr-2"></i> Edit Profil Akun
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="block w-full text-left px-4 py-3 text-red-200 hover:bg-red-900 font-medium">
                            <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar Aplikasi
                        </button>
                    </form>
                @else
                    <div class="px-4 py-2">
                        <a href="{{ route('login') }}" 
                        class="block w-full text-center px-4 py-3 bg-white text-red-700 font-bold rounded-lg shadow-inner">
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

            if (!btn || !menu) return; // safety guard

            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        });
    </script>

    {{-- CONTENT --}}
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <footer class="bg-[#B51233] text-red-200 pt-12 pb-8 px-6 md:px-20">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 border-b border-red-200 pb-8">
            {{-- Kolom 1: Logo & Alamat --}}
            <div class="md:col-span-2">
                <h3 class="text-xl font-bold text-white mb-3">PT Sinar Sosro Gunung Slamat</h3>
                <p class="text-sm leading-relaxed">
                    Kantor Pusat: Graha Rekso, Jl. Bulevard Artha Gading No. Kav. A1, Jakarta, Indonesia.<br>
                    Telepon: (021) 4585 6268<br>
                    Email: <a href="mailto:recruitment.hrd@sosro.com" class="text-white hover:underline">recruitment.hrd@sosro.com</a> / 
                        <a href="mailto:recruitment.ho@sosro.com" class="text-white hover:underline">recruitment.ho@sosro.com</a>
                </p>
                <p class="text-sm mt-2">
                    Jam Operasional: <br>
                    Senin - Jumat: 08:00 - 17:00 <br>
                    Sabtu - Minggu: Tutup
                </p>
            </div>

            {{-- Kolom 2: Layanan --}}
            <div>
                <h4 class="text-white font-semibold mb-3"></h4> 
                {{-- layanan --}}
                <ul class="space-y-2 text-sm">
                    {{-- <li><a href="#" class="hover:text-white transition">Produk Kami</a></li>
                    <li><a href="#" class="hover:text-white transition">Distribusi</a></li>
                    <li><a href="#" class="hover:text-white transition">Kemitraan</a></li>
                    <li><a href="#" class="hover:text-white transition">Kontak Bisnis</a></li> --}}
                </ul>
            </div>

            {{-- Kolom 3: Karir --}}
            <div>
                <h4 class="text-white font-semibold mb-3">Karir</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('lowongan') }}" class="hover:text-white transition">Lowongan</a></li>
                    <li><a href="#" class="hover:text-white transition">Budaya Kerja</a></li>
                    <li><a href="#" class="hover:text-white transition">Pelatihan</a></li>
                </ul>
            </div>

            {{-- Kolom 4: Info & Sosial Media --}}
            <div>
                <h4 class="text-white font-semibold mb-3">Info</h4>
                <ul class="space-y-2 text-sm mb-4">
                    <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                </ul>

                <br>
                <div class="flex space-x-5">
                    <a href="https://www.instagram.com/sosrocareer/" class="text-red-100 hover:text-white"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="https://www.linkedin.com/company/pt-sinar-sosro-gunung-slamat/" class="text-red-100 hover:text-white"><i class="fab fa-linkedin text-xl"></i></a>
                    <a href="https://www.tiktok.com/@lifeatsosro" class="text-red-100 hover:text-white"><i class="fab fa-tiktok text-xl"></i></a>
                    {{-- <a href="#" class="text-red-100 hover:text-white"><i class="fab fa-whatsapp text-xl"></i></a> --}}
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="flex flex-col md:flex-row justify-between items-center mt-6 text-sm text-white">
            <p>© {{ date('Y') }} PT Sinar Sosro Gunung Slamat. All rights reserved.</p> <br>
            <p>Made with ❤️ by <span class="text-red-100 font-semibold">Sosro Career Team</span></p>
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
                nav.classList.add('bg-[#B51233]', 'shadow-lg', 'py-0');
                nav.classList.remove('py-2', 'bg-transparent');
            } else {
                // State awal (Transparent)
                nav.classList.remove('bg-[#B51233]', 'shadow-lg', 'py-0');
                nav.classList.add('py-2', 'bg-transparent');
            }
        });
    </script>

    {{-- carousel beranda --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>
</html>
