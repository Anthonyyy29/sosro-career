<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <div x-data="{ open: false }" class="flex">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r h-screen p-4 fixed md:relative z-20
                       transform md:transform-none transition duration-300"
               :class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

            <h2 class="text-xl font-bold mb-6">Admin Panel</h2>

            <nav class="space-y-2">

                <a href="{{ route('admin.dashboard') }}"
                   class="block px-4 py-2 rounded hover:bg-gray-200
                   {{ request()->routeIs('admin.dashboard') ? 'bg-gray-300 font-semibold' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.lowongan') }}"
                   class="block px-4 py-2 rounded hover:bg-gray-200
                   {{ request()->routeIs('admin.lowongan*') ? 'bg-gray-300 font-semibold' : '' }}">
                    Job Posting
                </a>

                <a href="{{ route('admin.applicants') }}"
                   class="block px-4 py-2 rounded hover:bg-gray-200
                   {{ request()->routeIs('admin.applicants*') ? 'bg-gray-300 font-semibold' : '' }}">
                    Pelamar
                </a>

            </nav>
        </aside>

        <!-- OVERLAY (untuk mobile) -->
        <div class="fixed inset-0 bg-black bg-opacity-30 md:hidden"
             x-show="open" @click="open = false"></div>


        <!-- CONTENT -->
        <main class="flex-1 p-6 ml-0 md:ml-0 md:pl-72 w-full">
            <!-- TOGGLE BUTTON MOBILE -->
            <button @click="open = true" class="md:hidden mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            @yield('content')
        </main>

    </div>

</body>
</html>
