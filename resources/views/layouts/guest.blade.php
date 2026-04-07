<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" type="image/png" href="{{ asset('assets/images/logo sosro.webp') }}">
        <title>{{ config('app.name', 'Sosro Career') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .loader {
                border-top-color: transparent;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>

        <x-turnstile.scripts />

    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50/50">
        
        <nav x-data="{ open: false, userOpen: false }" class="bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-0 z-[100]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-18 md:h-20 p-2"> 
                    <div class="flex items-center">
                        {{-- <a href="{{ route('beranda') }}" class="text-xl md:text-2xl font-black text-red-600 tracking-tighter transition hover:opacity-80"> --}}
                        <a href="{{ route('beranda') }}" class="text-xl md:text-2xl font-black text-red-600 tracking-tighter transition hover:opacity-80">
                            SOSRO<span class="text-gray-800 font-light">CAREER</span>
                        </a>
                    </div>

                    <div class="hidden md:flex md:items-center md:space-x-8">
                        <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'nav-link-active' : 'nav-link' }} hover:text-red-600">Beranda</a>
                        <a href="{{ route('tentang') }}" class="nav-link hover:text-red-600">Tentang</a>
                        <a href="{{ route('program') }}" class="nav-link hover:text-red-600">Program</a>
                        <a href="{{ route('lowongan') }}" class="{{ request()->routeIs('lowongan') ? 'nav-link-active' : 'nav-link' }} hover:text-red-600">Lowongan</a>
                        <a href="{{ route('kontak') }}" class="nav-link hover:text-red-600">Kontak</a>
                    </div>

                    <div class="hidden md:flex md:items-center">
                        @auth
                            <div class="relative ml-3">
                                <button @click="userOpen = !userOpen" @click.away="userOpen = false" class="group flex items-center space-x-3 bg-gray-50 px-3 py-2 rounded-2xl border border-transparent hover:border-gray-200 transition-all duration-300">
                                    <div class="flex flex-col items-end leading-tight mr-1">
                                        <span class="text-[13px] font-bold text-gray-700 group-hover:text-red-600 transition-colors">{{ Auth::user()->name }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium tracking-wide uppercase">Kandidat</span>
                                    </div>
                                    <div class="h-9 w-9 bg-red-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-red-200 ring-2 ring-white">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-red-600 transition-transform" :class="{'rotate-180': userOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div x-show="userOpen" 
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     style="display: none;"
                                     class="absolute right-0 mt-3 w-56 bg-white border border-gray-100 rounded-[1.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] py-2 z-50 overflow-hidden">
                                    
                                    <div class="px-4 py-3 bg-gray-50/50 mb-2 border-b border-gray-100">
                                        <p class="text-xs text-gray-400 font-medium">Email Terdaftar</p>
                                        <p class="text-sm font-semibold text-gray-700 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all">
                                        <svg class="w-4 h-4 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Akun Saya
                                    </a>


                                    {{-- Trial by Rizky --}}
                                    <a href="{{ route('applicant.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all">
                                        <svg class="w-4 h-4 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Dashboard
                                    </a>

                                    {{-- Trial by Rizky --}}
                                    <a href="{{ route('applicant.applications.index') }}" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all">
                                        <svg class="w-4 h-4 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Lamaran Saya
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 transition-all">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Keluar Akun
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="bg-red-600 text-white px-7 py-2.5 rounded-full font-bold text-sm hover:bg-red-700 transition shadow-lg shadow-red-200 active:scale-95">
                                Masuk
                            </a>
                        @endauth
                    </div>

                    <div class="flex items-center md:hidden">
                        <button @click="open = !open" class="p-2.5 rounded-xl bg-gray-50 text-gray-600 hover:text-red-600 hover:bg-red-50 transition-all active:scale-90">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 style="display: none;"
                 class="md:hidden bg-white border-t border-gray-100 overflow-hidden shadow-2xl rounded-b-[2rem]">
                
                <div class="px-5 pt-6 pb-8 space-y-4"> @auth
                        <div class="flex items-center p-4 mb-6 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="h-14 w-14 bg-red-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl mr-4 shadow-md shadow-red-100">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-base font-bold text-gray-800 truncate">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500 font-medium truncate">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                    @endauth
                    
                    <div class="grid grid-cols-1 gap-2 text-red-600">
                        <a href="{{ route('beranda') }}" class="mobile-nav-link {{ request()->routeIs('beranda') ? 'bg-red-50 text-red-600' : '' }} hover:font-semibold">Beranda</a>
                        <a href="{{ route('tentang') }}" class="mobile-nav-link hover:font-semibold">Tentang</a>
                        <a href="{{ route('program') }}" class="mobile-nav-link hover:font-semibold">Program</a>
                        <a href="{{ route('lowongan') }}" class="mobile-nav-link {{ request()->routeIs('lowongan') ? 'bg-red-50 text-red-600' : '' }} hover:font-semibold">Lowongan</a>
                        <a href="{{ route('kontak') }}" class="mobile-nav-link hover:font-semibold">Kontak</a>
                        {{-- hanya satu yang paling bawah ini sepertinya memang tidak terpakai --}}
                        {{-- <a href="{{ route('applicant.applications.index') }}" class="hover:text-red-600"> Lamaran Saya </a> --}}
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="mobile-nav-link flex items-center">
                                <svg class="w-5 h-5 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Akun Saya
                            </a>
                            {{-- Trial by Rizky --}}
                            <a href="{{ route('applicant.applications.index') }}" class="mobile-nav-link flex items-center">
                                <svg class="w-5 h-5 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Lamaran Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-4 text-red-600 font-bold hover:bg-red-50 rounded-2xl transition-all flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Keluar Aplikasi
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center bg-red-600 text-white py-4 rounded-2xl font-bold shadow-xl shadow-red-100 active:scale-[0.98] transition-transform">
                                Masuk ke Akun
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="min-h-screen">
            {{ $slot }}
        </main>

        <footer class="bg-white border-t border-gray-100 py-5">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-[10px] md:text-xs font-semibold uppercase tracking-[0.2em]">
            &copy; {{ date('Y') }} PT Sinar Sosro Gunung Slamat<br class="md:hidden"> • Career Portal
            </p>
        </div>
        </footer>

        <style>
            .nav-link {
                @apply text-[14px] font-semibold text-gray-500 hover:text-red-600 transition-colors duration-200 py-2 border-b-2 border-transparent;
            }
            .nav-link-active {
                @apply text-[14px] font-extrabold text-red-600 border-b-2 border-red-600 py-2;
            }
            /* Tombol Navigasi Mobile diperbesar target kliknya */
            .mobile-nav-link {
                @apply block px-4 py-4 text-[15px] font-bold text-gray-700 hover:bg-gray-50 hover:text-red-600 rounded-2xl transition-all duration-200;
            }
            
            html { scroll-behavior: smooth; }
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #f9fafb; }
            ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #d1d5db; }

            /* Menghindari layout shift saat menu dibuka */
            [x-cloak] { display: none !important; }
        </style>

        {{-- Notifikasi Biodata berhasil diperbarui --}}
        @if(session('success'))
            <div id="toast-success" class="fixed top-5 right-5 z-[100] transition-all duration-500 transform translate-x-full">
                <div class="flex items-center w-full max-w-xs p-4 text-gray-700 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-green-100" role="alert">
                    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-50 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3 text-sm font-bold uppercase tracking-wide text-gray-800">
                        {{ session('success') }}
                    </div>
                    <button type="button" onclick="closeToast()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg p-1.5 inline-flex h-8 w-8">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                {{-- Progress Bar --}}
                <div class="w-full bg-gray-100 h-1 mt-1 rounded-full overflow-hidden">
                    <div id="toast-progress" class="bg-green-500 h-full w-full transition-all duration-[3000ms] ease-linear"></div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toast = document.getElementById('toast-success');
                    const progress = document.getElementById('toast-progress');
                    
                    // Munculkan toast
                    setTimeout(() => {
                        toast.classList.remove('translate-x-full');
                        progress.style.width = '0%';
                    }, 100);

                    // Hilangkan otomatis setelah 3 detik
                    setTimeout(() => {
                        closeToast();
                    }, 3100);
                });

                function closeToast() {
                    const toast = document.getElementById('toast-success');
                    toast.classList.add('translate-x-full');
                    setTimeout(() => toast.remove(), 500);
                }
            </script>
        @endif

        {{-- Notifikasi Error Umum --}}
        @if(session('error') || $errors->any())
            <div id="toast-error" class="fixed top-5 right-5 z-[100] transition-all duration-500 transform translate-x-full">
                <div class="flex items-center w-full max-w-xs p-4 text-gray-700 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.2)] border border-red-100" role="alert">
                    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-50 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3 text-sm font-bold uppercase tracking-wide text-gray-800">
                        @if(session('error'))
                            {{ session('error') }}
                        @else
                            Terjadi kesalahan input data.
                        @endif
                    </div>
                    <button type="button" onclick="closeErrorToast()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg p-1.5 inline-flex h-8 w-8">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const errorToast = document.getElementById('toast-error');
                    if(errorToast) {
                        setTimeout(() => {
                            errorToast.classList.remove('translate-x-full');
                        }, 100);
                        
                        // Error biarkan lebih lama (5 detik) karena user perlu baca apa yang salah
                        setTimeout(() => {
                            closeErrorToast();
                        }, 5000);
                    }
                });

                function closeErrorToast() {
                    const errorToast = document.getElementById('toast-error');
                    if(errorToast) {
                        errorToast.classList.add('translate-x-full');
                        setTimeout(() => errorToast.remove(), 500);
                    }
                }
            </script>
        @endif
        
    </body>
    <script>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    // Simpan lebar asli agar tombol tidak menciut
                    const originalWidth = submitBtn.offsetWidth;
                    submitBtn.style.width = originalWidth + 'px';
                    
                    // Ubah konten menjadi spinner
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <div class="flex items-center justify-center">
                            <div class="w-5 h-5 border-2 border-white border-t-transparent border-solid rounded-full animate-spin"></div>
                            <span class="ml-2 text-xs uppercase tracking-widest">Memproses...</span>
                        </div>
                    `;
                }
            });
        });
    </script>
</html>