{{-- NAVBAR UTAMA (Managed by Alpine.js) --}}
@php
    $isLoginRoute = request()->routeIs('guest.login');
    $isRegisterRoute = request()->routeIs('guest.register');
@endphp

<nav id="main-navbar" x-data="{ mobileMenuOpen: false }"
    class="max-w-[1800px] w-full px-4 {{ $isLoginRoute || $isRegisterRoute ? 'md:px-8 bg-[#B11116] relative' : 'md:px-[200px] py-3 fixed z-10 transition-all duration-500 ease-in-out' }} text-white">

    <div class="flex justify-between items-center py-3">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('assets/images/SGS Logo-Putih.webp') }}" alt="Logo Sinar Sosro Gunung Slamat"
                class="h-11 w-auto">
        </div>

        {{-- Navbar Menu for Desktop Size --}}
        {{-- 
        <div class="hidden md:flex space-x-8">
            <a href="{{ route('guest.home') }}"
                class="relative group font-medium {{ request()->routeIs('guest.home') ? 'text-white' : 'text-gray-100' }}">
                Beranda
                <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300 {{ request()->routeIs('guest.home') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('guest.about') }}"
                class="relative group font-medium {{ request()->routeIs('guest.about') ? 'text-white' : 'text-gray-100' }}">
                Tentang
                <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300 {{ request()->routeIs('guest.about') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('guest.program') }}"
                class="relative group font-medium {{ request()->routeIs('guest.program') ? 'text-white' : 'text-gray-100' }}">
                Program
                <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300 {{ request()->routeIs('guest.program') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('guest.job') }}"
                class="relative group font-medium {{ request()->routeIs('guest.job') ? 'text-white' : 'text-gray-100' }}">
                Lowongan
                <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300 {{ request()->routeIs('guest.job') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('kontak') }}"
                class="relative group font-medium {{ request()->routeIs('kontak') ? 'text-white' : 'text-gray-100' }}">
                Kontak
                <span class="absolute left-0 -bottom-1 h-[1px] bg-white transition-all duration-300 {{ request()->routeIs('kontak') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
        </div>
        --}}

        <div class="hidden md:flex items-center space-x-4">
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center space-x-3 bg-black/20 hover:bg-black/30 backdrop-blur-md border border-white/10 p-1.5 pl-4 pr-1.5 rounded-full transition-all duration-300 focus:outline-none group shadow-sm">

                        <div class="text-right hidden sm:block">
                            <p class="text-[11px] font-bold text-white tracking-wide leading-none mb-1 drop-shadow-sm">
                                {{ Auth::user()->name }}
                            </p>
                            <p
                                class="text-[9px] font-medium text-gray-200/80 leading-none truncate max-w-[130px] drop-shadow-sm">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                        <div
                            class="h-8 w-8 rounded-full bg-red-600 text-white flex items-center justify-center font-bold shadow-md border border-white/20 transition group-hover:scale-105 group-active:scale-95">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-cloak
                        class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-2xl py-2 z-[70] border border-gray-100 overflow-hidden">

                        <div class="px-4 py-3 border-b border-gray-50 mb-1 sm:hidden">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('dashboard') }}"
                            class="group flex items-center px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-red-50 transition">
                            <div
                                class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center mr-3 group-hover:bg-gray-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-4 text-[#B11116]">
                                    <path fill-rule="evenodd"
                                        d="M4.25 2A2.25 2.25 0 0 0 2 4.25v2.5A2.25 2.25 0 0 0 4.25 9h2.5A2.25 2.25 0 0 0 9 6.75v-2.5A2.25 2.25 0 0 0 6.75 2h-2.5Zm0 9A2.25 2.25 0 0 0 2 13.25v2.5A2.25 2.25 0 0 0 4.25 18h2.5A2.25 2.25 0 0 0 9 15.75v-2.5A2.25 2.25 0 0 0 6.75 11h-2.5Zm9-9A2.25 2.25 0 0 0 11 4.25v2.5A2.25 2.25 0 0 0 13.25 9h2.5A2.25 2.25 0 0 0 18 6.75v-2.5A2.25 2.25 0 0 0 15.75 2h-2.5Zm0 9A2.25 2.25 0 0 0 11 13.25v2.5A2.25 2.25 0 0 0 13.25 18h2.5A2.25 2.25 0 0 0 18 15.75v-2.5A2.25 2.25 0 0 0 15.75 11h-2.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            Dashboard Pelamar
                        </a>

                        <hr class="my-1 border-gray-50">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="group flex items-center w-full px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition text-left">
                                <div
                                    class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center mr-3 group-hover:bg-red-100 transition">
                                    <i class="fa-solid fa-power-off text-red-600 text-xs"></i>
                                </div>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                @if (request()->routeIs('guest.login'))
                    <a href="{{ route('guest.register') }}"
                        class="px-6 py-2 bg-white text-red-700 font-bold rounded-full hover:bg-gray-100 transition shadow-md hover:shadow-lg transform active:scale-95">
                        Daftar
                    </a>
                @else
                    <a href="{{ route('guest.login') }}"
                        class="px-6 py-2 bg-white text-red-700 font-bold rounded-full hover:bg-gray-100 transition shadow-md hover:shadow-lg transform active:scale-95">
                        Masuk
                    </a>
                @endif
            @endauth

            <div class="relative inline-block text-left" x-data="{ open: false, lang: 'ID', flag: 'id' }">
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center space-x-2 py-2 focus:outline-none">
                    <div class="w-6 h-6 rounded-full overflow-hidden border border-white/50 shadow-sm flex-shrink-0">
                        <img :src="`https://flagcdn.com/w40/${flag}.png`" :alt="lang"
                            class="w-full h-full object-cover">
                    </div>
                    <span class="text-white font-bold text-sm tracking-tight" x-text="lang">ID</span>
                    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 text-white transition-transform duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-cloak
                    class="absolute right-0 mt-2 w-32 bg-white rounded-2xl shadow-2xl py-2 z-[70] border border-gray-100 overflow-hidden">
                    <button @click="lang = 'ID'; flag = 'id'; open = false; changeLanguage('id')"
                        class="flex items-center space-x-3 px-4 py-2 w-full text-left hover:bg-red-50 transition">
                        <img src="https://flagcdn.com/w40/id.png" class="w-5 h-5 rounded-full object-cover"
                            alt="ID">
                        <span class="text-gray-800 text-sm font-semibold">Indonesia</span>
                    </button>
                    <button @click="lang = 'EN'; flag = 'gb'; open = false; changeLanguage('en')"
                        class="flex items-center space-x-3 px-4 py-2 w-full text-left hover:bg-red-50 transition border-t border-gray-50">
                        <img src="https://flagcdn.com/w40/gb.png" class="w-5 h-5 rounded-full object-cover"
                            alt="EN">
                        <span class="text-gray-800 text-sm font-semibold">English</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="md:hidden flex items-center">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="focus:outline-none text-white p-2">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4" x-cloak
        class="md:hidden bg-[#B11116] rounded-b-2xl shadow-xl mt-2 overflow-hidden">

        <div class="px-4 py-4" x-data="{ currentLang: '{{ strtoupper(App::getLocale()) }}' }">
            <div class="grid grid-cols-2 gap-3">
                <button @click="changeLanguage('id'); currentLang = 'ID'"
                    :class="currentLang === 'ID' ? 'bg-white text-red-700' :
                        'bg-[#B11116] text-white border border-red-400'"
                    class="flex items-center justify-center space-x-2 py-3 rounded-xl font-bold transition-all active:scale-95">
                    <img src="https://flagcdn.com/w40/id.png" class="w-5 h-5 rounded-full object-cover shadow-sm"
                        alt="ID">
                    <span>ID</span>
                </button>

                <button @click="changeLanguage('en'); currentLang = 'EN'"
                    :class="currentLang === 'EN' ? 'bg-white text-red-700' :
                        'bg-[#B11116] text-white border border-red-400'"
                    class="flex items-center justify-center space-x-2 py-3 rounded-xl font-bold transition-all active:scale-95">
                    <img src="https://flagcdn.com/w40/gb.png" class="w-5 h-5 rounded-full object-cover shadow-sm"
                        alt="EN">
                    <span>EN</span>
                </button>
            </div>
        </div>

        <div class="border-t border-red-800/60 mt-2 pt-2 pb-4">
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
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 px-4 py-3 text-white hover:bg-red-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd"
                            d="M4.25 2A2.25 2.25 0 0 0 2 4.25v2.5A2.25 2.25 0 0 0 4.25 9h2.5A2.25 2.25 0 0 0 9 6.75v-2.5A2.25 2.25 0 0 0 6.75 2h-2.5Zm0 9A2.25 2.25 0 0 0 2 13.25v2.5A2.25 2.25 0 0 0 4.25 18h2.5A2.25 2.25 0 0 0 9 15.75v-2.5A2.25 2.25 0 0 0 6.75 11h-2.5Zm9-9A2.25 2.25 0 0 0 11 4.25v2.5A2.25 2.25 0 0 0 13.25 9h2.5A2.25 2.25 0 0 0 18 6.75v-2.5A2.25 2.25 0 0 0 15.75 2h-2.5Zm0 9A2.25 2.25 0 0 0 11 13.25v2.5A2.25 2.25 0 0 0 13.25 18h2.5A2.25 2.25 0 0 0 18 15.75v-2.5A2.25 2.25 0 0 0 15.75 11h-2.5Z"
                            clip-rule="evenodd" />
                    </svg>
                    Dashboard Pelamar
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="block w-full text-left px-4 py-3 text-white hover:bg-red-900 font-medium transition">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar Aplikasi
                    </button>
                </form>
            @else
                <div class="px-4 py-2">
                    <a href="{{ route('guest.login') }}"
                        class="block w-full text-center px-4 py-3 bg-white text-red-700 font-bold rounded-xl shadow-md transition active:scale-95">
                        Masuk ke Akun
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
