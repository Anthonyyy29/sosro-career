<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Sosro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Datatables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.min.css">

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
            margin-bottom: 8px;
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
            color: #6B7280; /* Warna ikon default */
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
            color: #1F2937; /* Warna ikon saat aktif (sama dengan teks) */
        }
        
        /* Hover State Non-Aktif */
        .sidebar a:hover:not(.active) {
            background: #F3F4F6;
            color: #1F2937;
        }

        .sidebar a:hover:not(.active) svg {
            color: #1F2937;
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

    {{-- Datatables (lihat baris data) --}}
    <style>
        /* Menghilangkan panah ganda pada dropdown DataTables */
        .dt-length select {
            appearance: none !important;         /* Menghilangkan panah default browser */
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            
            /* Gunakan background panah tunggal yang bersih */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.5rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.5em 1.5em !important;
            
            padding-right: 2.5rem !important;    /* Memberi ruang agar teks tidak tertimpa panah */
            padding-left: 0.75rem !important;
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            background-color: #ffffff !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            cursor: pointer !important;
            outline: none !important;
        }

        /* Memastikan fokus terlihat rapi */
        .dt-length select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important;
        }

        /* Jarak teks "Tampilkan ... data" */
        .dt-length label {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            font-size: 0.875rem !important;
            color: #374151 !important;
        }
    </style>

</head>

<body class="bg-gray-100">

    <div id="menuButton">☰</div>

    <div class="flex min-h-screen">

        <div id="sidebar" class="sidebar">
            <div>
                <div class="flex items-center mb-2">
                    <img src="{{ asset('assets/images/SGS Logo-Color.png') }}" 
                    alt="Logo Sosro" 
                    class="h-15 w-auto">
                </div> 
                <p style="border-bottom: 1px solid #ffece7; width: 100%; margin-bottom: 10px;"></p>
                
                {{-- 1. Dashboard (Icon: Home) --}}
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                        <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                    </svg>
                    Dashboard
                </a>

                {{-- 2. User Profile (Icon: User) --}}
                <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                    </svg>
                    User Profile
                </a>

                {{-- 3. Lowongan (Icon: Briefcase) --}}
                {{-- <a href="{{ route('admin.lowongan') }}" class="{{ request()->routeIs('admin.lowongan') ? 'active' : '' }}"> --}}
                <a href="{{ route('admin.lowongan.index') }}" class="{{ request()->routeIs('admin.lowongan.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                        <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                    </svg>
                    Lowongan
                </a>

                {{-- 4. Pelamar (Icon: Users) --}}
                <a href="{{ route('admin.applicants') }}" class="{{ request()->routeIs('admin.applicants') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M4.5 3.75a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V6.75a3 3 0 0 0-3-3h-15Zm4.125 3a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Zm-3.873 8.703a4.126 4.126 0 0 1 7.746 0 .75.75 0 0 1-.351.92 7.47 7.47 0 0 1-3.522.877 7.47 7.47 0 0 1-3.522-.877.75.75 0 0 1-.351-.92ZM15 8.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15ZM14.25 12a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H15a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15Z" clip-rule="evenodd" />
                    </svg>
                    Pelamar
                </a>
            </div>

            {{-- Logout Button di bagian bawah sidebar --}}
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-auto pt-5">
                @csrf
                <button type="submit" class="w-full logout-btn flex items-center justify-start">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H9" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <div class="flex-1 min-w-0 overflow-x-hidden">

            <header class="bg-white shadow-sm h-16 flex items-center justify-end px-6 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <svg class="w-6 h-6 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    
                    <span class="h-6 w-px bg-gray-200"></span>

                    <div class="flex items-center gap-2 cursor-pointer">
                        <span class="text-gray-700 font-medium text-sm">{{ auth()->user()->name ?? 'Admin User' }}</span>
                        <img src="{{ asset('assets/images/profile1.png') }}" class="w-9 h-9 rounded-full border border-gray-300" alt="Profile">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </header>

            <main class="p-8 main-content">
                @if (session('success'))
                    <div 
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
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
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                layout: {
                    topStart: 'pageLength', // Memastikan elemen diletakkan dengan benar
                    topEnd: 'search'
                },
                language: { 
                    search: "Cari:", 
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        }
    });
</script>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>
</html>