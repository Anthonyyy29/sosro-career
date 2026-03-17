<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Sosro Career</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/webp" href="{{ asset('assets/images/logo sosro.webp') }}">

    {{-- Datatables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.min.css">

    {{-- TOMBOL DATATABLES --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    {{-- Alpine.js untuk lonceng notifikasi --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Sidebar - Menggunakan Warna Background Putih Bersih */
        .sidebar {
            width: 250px;
            background: #FFFFFF;
            min-height: 100vh;
            color: #1F2937;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        /* Styling Link Navigasi */
        .sidebar a {
            display: flex; /* WAJIB: Menggunakan Flexbox untuk ikon dan teks */
            align-items: center; /* Menyejajarkan ikon dan teks secara vertikal */
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            text-decoration: none;
            color: #4B5563;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
        }

        /* Styling Ikon di Sidebar */
        .sidebar a svg {
            width: 20px; /* Ukuran ikon */
            height: 20px;
            margin-right: 12px; /* Jarak antara ikon dan teks */
            color: #ff8622; /* Warna ikon default */
            transition: color 0.2s;
        }

        /* Active State - Menggunakan Merah/Oranye Aksen Sosro */
        .sidebar a.active {
            background: #ffbf34; 
            /* background: #ff8622;  */
            color: #1F2937;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(255, 134, 34, 0.2), 0 2px 4px -1px rgba(255, 134, 34, 0.1);
            transform: translateY(-1px);
        }

        .sidebar a.active svg {
            color: white; /* Warna ikon saat aktif (sama dengan teks) */
        }
        
        /* Hover State Non-Aktif */
        .sidebar a:hover:not(.active) {
            background: #F3F4F6;
            color: #1F2937;
        }

        .sidebar a:hover:not(.active) svg {
            color: #ffbf34;
        }

        /* Styling Tombol Logout */
        .logout-btn {
            background-color: #EF4444;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .logout-btn:hover {
            background-color: #DC2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3), 0 2px 4px -1px rgba(239, 68, 68, 0.1);
        }

        /* Responsive */
        #menuButton {
            color: #1F2937;
        }

        @media (min-width: 993px) {
            #menuButton {
                display: none !important; /* Pastikan tombol hilang di desktop */
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                position: fixed; 
                left: -260px;
                top: 0;
                height: 100%;
                transition: 0.3s ease-in-out;
                z-index: 150;
                overflow-y: auto;
            }
            .sidebar.open {
                left: 0;
            }
            #menuButton {
                display: block; 
                position: fixed;
                top: 8px;
                left: 25px;
                font-size: 26px;
                cursor: pointer;
                z-index: 100; 
                background-color: white;
                padding: 5px 10px;
                border-radius: 4px;
                box-shadow: 0 2px 5px rgba(2, 1, 1, 0.1);
            }

            .main-content {
                padding-top: 70px;
            }
        }
    </style>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{-- CUSTOM DATATABLES  --}}
    <style>

    /* 1. Wrapper & Layouting */
    .dt-container {
        @apply pt-4; /* Jika menggunakan tailwind, jika tidak gunakan: padding-top: 1rem; */
    }

    /* 2. Mengecilkan Info Teks (Menampilkan x-y data) */
    .dt-info {
        font-size: 0.813rem !important;
        color: #64748b !important; /* Slate 500 */
        font-weight: 400;
    }

    /* 3. Pagination Styling */
    .dt-paging {
        display: flex !important;
        gap: 0.35rem !important;
        margin-top: 1rem !important;
    }

    .dt-paging-button {
        border: 1px solid #e2e8f0 !important; /* Slate 200 */
        border-radius: 0.5rem !important; /* Rounded LG */
        padding: 0.35rem 0.85rem !important;
        font-size: 0.813rem !important;
        font-weight: 600 !important;
        background-color: #ffffff !important;
        color: #475569 !important; /* Slate 600 */
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer !important;
    }

    .dt-paging-button:hover:not(.disabled):not(.current) {
        background-color: #f8fafc !important; /* Slate 50 */
        border-color: #cbd5e1 !important;
        color: #1e293b !important;
    }

    .dt-paging-button.current {
        background-color: #85c6ff !important; /* Blue 600 */
        color: #ffffff !important;
        border-color: #85c6ff !important;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    .dt-paging-button.disabled {
        opacity: 0.4;
        cursor: not-allowed !important;
        background-color: #f1f5f9 !important;
    }

    /* 4. Dropdown "Show Entries" Styling */
    .dt-length select {
        appearance: none !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        background-position: right 0.5rem center !important;
        background-repeat: no-repeat !important;
        background-size: 1.25em 1.25em !important;
        padding: 0.3rem 2rem 0.3rem 0.75rem !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        font-size: 0.813rem !important;
        outline: none !important;
    }

    .dt-length label {
        font-size: 0.813rem !important;
        color: #64748b !important;
    }

    /* 5. Search Input Styling */
    .dt-search input {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        padding: 0.4rem 0.75rem !important;
        font-size: 0.813rem !important;
        outline: none !important;
        transition: all 0.2s;
    }

    .dt-search input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    </style>

</head>

<body class="bg-gray-100">

    <div id="menuButton" class="no-print">☰</div>

    <div class="flex min-h-screen">

        <div id="sidebar" class="sidebar">
            <div>
                <div class="flex items-center mb-2">
                    <img src="{{ asset('assets/images/SGS Logo-Color.webp') }}" 
                    alt="Logo PT Sinar Sosro Gunung Slamat" 
                    class="h-15 w-auto">
                </div> 
                <p style="border-bottom: 1px solid #ffece7; width: 100%; margin-bottom: 10px;"></p>
                
                {{-- 1. Dashboard (Icon: Home) --}}
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z" clip-rule="evenodd" />
                    </svg>
                    Dashboard
                </a>

                {{-- 2. Profile (Icon: User) --}}
                <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                    </svg>
                    Profil Saya
                </a>

                {{-- 3. Lowongan (Icon: Briefcase) --}}
                <a href="{{ route('admin.lowongan.index') }}" class="{{ request()->routeIs('admin.lowongan.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M7.5 5.25a3 3 0 0 1 3-3h3a3 3 0 0 1 3 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0 1 12 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 0 1 7.5 5.455V5.25Zm7.5 0v.09a49.488 49.488 0 0 0-6 0v-.09a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5Zm-3 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                        <path d="M3 18.4v-2.796a4.3 4.3 0 0 0 .713.31A26.226 26.226 0 0 0 12 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 0 1-6.477-.427C4.047 21.128 3 19.852 3 18.4Z" />
                    </svg>
                    Lowongan
                </a>

                {{-- 4. Pelamar (Icon: Users) --}}
                <a href="{{ route('admin.applicants') }}" class="{{ request()->routeIs('admin.applicants*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M4.5 3.75a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V6.75a3 3 0 0 0-3-3h-15Zm4.125 3a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Zm-3.873 8.703a4.126 4.126 0 0 1 7.746 0 .75.75 0 0 1-.351.92 7.47 7.47 0 0 1-3.522.877 7.47 7.47 0 0 1-3.522-.877.75.75 0 0 1-.351-.92ZM15 8.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15ZM14.25 12a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H15a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15Z" clip-rule="evenodd" />
                    </svg>
                    Pelamar
                </a>

                {{-- 5. Laporan (Icon: Document) --}}
                <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                        <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                    </svg>
                    Laporan
                </a>

                {{-- 6. Pengguna (Icon: Users) --}}
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                    </svg>
                    Pengguna
                </a>
            </div>

            {{-- Logout Button di bagian bawah sidebar --}}
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-auto pt-5">
                @csrf
                <button type="submit" class="w-full logout-btn flex items-center justify-start">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 mr-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H9" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <div class="flex-1 min-w-0 overflow-x-hidden">

            <header class="bg-white shadow-sm h-16 flex items-center justify-end px-6 sticky top-0 z-30 no-print" x-data="{ open: false }">
                <div class="flex items-center gap-4">
                    {{-- Notifikasi --}}
                    <div x-data="{ notifCount: 0 }">
                        <!-- Notifikasi -->
                        <button class="relative p-1 text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span x-show="notifCount > 0" class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        </button>
                    </div>
                    
                    <span class="h-6 w-px bg-gray-200"></span>

                    {{-- Dropdown Profile --}}
                    <div class="relative">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none group">
                            <span class="text-gray-700 font-bold text-sm group-hover:text-red-600 transition-colors">{{ auth()->user()->name ?? 'Admin User' }}</span>
                            <img src="{{ auth()->user()->photo ? asset('storage/photos/' . auth()->user()->photo) . '?' . time() : asset('assets/images/images.png') }}" class="w-9 h-9 rounded-full border-2 border-transparent group-hover:border-red-500 transition-all shadow-sm" alt="Profile">
                            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        {{-- Menu Dropdown --}}
                        <div x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            style="display: none;"
                            class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                            
                            <div class="px-4 py-2 border-bottom border-gray-50 mb-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menu Akun</p>
                            </div>

                            <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profil Saya
                            </a>

                            <hr class="my-1 border-gray-50">

                            {{-- Form Logout --}}
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Keluar Sistem
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-8 main-content">
                @if (session('success'))
                    <div 
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 10000)"
                        class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3 relative"
                    >
                        <strong class="font-semibold">Berhasil!</strong>
                        <span class="ml-1">{{ session('success') }}</span>

                        <button 
                            @click="show = false"
                            class="absolute top-2 right-5 bottom-2 text-green-700 hover:text-green-900"
                        >
                            <b>x</b>
                        </button>
                    </div>
                @endif
                @yield('content')
            </main>

        </div>

    </div>

<script>
    const sidebar = document.getElementById("sidebar");
    const btn = document.getElementById("menuButton");

    const isMobile = () => window.innerWidth <= 992;

    btn.addEventListener("click", () => {
        if (isMobile()) {
            sidebar.classList.add("open"); 
            btn.style.display = 'none'; 
        }
    });

    // Logika menutup sidebar ketika area luar diklik
    document.addEventListener("click", (e) => {
        if (isMobile() && sidebar.classList.contains('open')) {
            if (!sidebar.contains(e.target) && !btn.contains(e.target)) {
                sidebar.classList.remove("open");
                btn.style.display = 'block';
            }
        }
    });

    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (isMobile() && sidebar.classList.contains('open')) {
                // Gunakan timeout untuk memberi waktu navigasi terjadi sebelum sidebar tertutup
                setTimeout(() => {
                    sidebar.classList.remove("open");
                    btn.style.display = 'block';
                }, 100); 
            }
        });
    });

    window.addEventListener('load', () => {
        // Logika inisialisasi tampilan tombol menu di mobile saat halaman dimuat
        if (isMobile() && !sidebar.classList.contains('open')) {
             btn.style.display = 'block';
        } else if (isMobile() && sidebar.classList.contains('open')) {
             btn.style.display = 'none';
        }
    });
</script>

{{-- Datatables --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        if ($('#table').length) {
            new DataTable('#table', {
                responsive: true,
                pageLength: 5,
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                layout: {
                    topStart: 'pageLength',
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging'
                },
                language: { 
                    search: "", 
                    searchPlaceholder: "Cari data...", 
                    lengthMenu: "_MENU_ data per halaman",
                    // Format teks info lebih ringkas
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Data tidak ditemukan",
                    infoFiltered: "(filter dari _MAX_ data)",
                    paginate: {
                        // Pakai simbol entitas HTML agar lebih clean
                        next: 'Lanjut &rarr;',
                        previous: '&larr; Balik'
                    }
                },
                // Menghilangkan garis bawah default setelah inisialisasi
                drawCallback: function() {
                    $('.datatable').css('border-bottom', 'none');
                }
            });
            
            // Menghapus label "Search" yang mengganggu
            $('.dt-search label').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();
        }
    });
</script>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

{{-- Tombol Datatables di Modul Laporan  --}}
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#tabel-laporan').DataTable({
            responsive: true,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]], // Pilihan jumlah data per halaman
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function (win) {
                        $(win.document.body).find('h1').css('text-align', 'center');
                        $(win.document.body).css('font-size', '10pt');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ],
            layout: {
                topStart: 'pageLength',
                topEnd: 'search',
                bottomStart: 'info',
                bottomEnd: 'paging'
            },
            language: { 
                search: "", 
                searchPlaceholder: "Cari data...", 
                lengthMenu: "_MENU_ data per halaman",
                // Format teks info lebih ringkas
                info: "Menampilkan _START_ - _END_ dari total <b>_TOTAL_</b> data yang ditemukan",
                infoEmpty: "Data tidak ditemukan",
                infoFiltered: "(filter dari _MAX_ data)",
                paginate: {
                    // Pakai simbol entitas HTML agar lebih clean
                    next: 'Lanjut &rarr;',
                    previous: '&larr; Balik'
                }
            },
            // Menghilangkan garis bawah default setelah inisialisasi
            drawCallback: function() {
                $('.datatable').css('border-bottom', 'none');
            }
        });
        
        // Menghapus label "Search" yang mengganggu
        $('.dt-search label').contents().filter(function() {
            return this.nodeType === 3;
        }).remove();

        // Sembunyikan container tombol bawaan DataTables (B) agar tidak muncul dobel
        table.buttons().container().hide();

        // Trigger tombol custom Anda
        $('#btn-print-custom').on('click', function() {
            table.button('.buttons-print').trigger();
        });
    });
</script>

</body>
</html>