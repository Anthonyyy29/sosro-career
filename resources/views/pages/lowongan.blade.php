<x-app-layout>

    {{-- ALERT HATI-HATI PENIPUAN --}}
    <div 
        x-data="{ open: true }"
        x-show="open"
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white max-w-lg mx-auto p-6 rounded-xl shadow-xl border-l-8 border-red-700 relative">
            
            <button 
                @click="open = false"
                class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 text-xl font-bold"
            >
                &times;
            </button>

            <h2 class="text-2xl font-bold text-red-700 mb-3">⚠ Waspada Penipuan!</h2>
            
            <p class="text-gray-700 leading-relaxed">
                <strong>HATI-HATI TERHADAP PENIPUAN LOWONGAN PEKERJAAN!</strong>
                <br><br>
                Waspadalah terhadap pihak yang mengatasnamakan 
                <strong>PT Sinar Sosro Gunung Slamat</strong>.
                Kami <strong>tidak pernah</strong> meminta pelamar membayar biaya apapun selama proses rekrutmen.
                <br><br>
                Pastikan hanya mengikuti prosedur resmi melalui:
                <br>
                • LinkedIn: <strong>PT Sinar Sosro Gunung Slamat</strong>  
                <br>
                • Instagram: <strong>@sosrocareer</strong>
                <br>
                • Tiktok: <strong>@lifeatsosro</strong>
                <br><br>
                Jika Anda menerima tawaran mencurigakan, segera laporkan!
            </p>
        </div>
    </div>

    {{-- HERO SECTION LOWONGAN --}}
    <section class="relative w-full h-screen overflow-hidden z-0 bg-black">
        <img src="{{ asset('assets/images/lowongan-hero.webp') }}" alt="Intip Keseruan Insan Sinar Sosro Gunung Slamat" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-red-900 via-red/80 to-transparent"></div>
        <div class="absolute inset-0 flex items-center justify-center px-6 md:px-20 max-w-7xl mx-auto">
            <div class="w-full text-center md:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl text-white font-bold uppercase leading-[1.1] md:max-w-[700px] drop-shadow-lg">
                    Bersama Kami, Menjadi Bagian Sinar Sosro Gunung Slamat
                </h1>
                <p class="text-lg md:text-3xl text-white mt-6 md:mt-8 leading-relaxed max-w-2xl">
                    Bergabunglah dengan tim kami, baik sebagai talenta muda maupun profesional berpengalaman. Pilih kategori yang sesuai untuk mengembangkan potensi karirmu.
                </p>
            </div>
        </div>
    </section>

    {{-- SECTION LOWONGAN --}}
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">

            {{-- Badge Jumlah Lowongan --}}
            <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-red-50 border-red-100 mb-4">
                <p class="text-red-700 font-semibold text-sm">Posisi terbuka: 
                    <span id="jobCount" class="bg-red-600 text-white px-2 py-0.5 rounded-full mx-1">
                    {{ $lowongan->count() }}
                    </span>
                </p>
            </div>

            {{-- SEARCH + FILTER CONTAINER --}}
            <div class="flex flex-col lg:flex-row gap-4 justify-center items-center mb-10 font-sans">

                {{-- WRAPPER SEARCH (Input + Button Jadi Satu) --}}
                <div class="relative flex items-center w-full lg:w-2/5 bg-white rounded-full shadow-md overflow-hidden">
                    <div class="pl-5 text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        id="searchInput"
                        type="text" 
                        placeholder="Cari lowongan..."
                        class="w-full px-4 py-3.5 border-none focus:ring-0 focus:outline-none text-red-800 placeholder-red-300 bg-transparent font-semibold"
                    />
                    <button class="bg-[#B11116] text-white px-8 py-2.5 rounded-full font-bold mr-1.5 hover:bg-red-800 transition shadow-md">
                        Cari
                    </button>
                </div>

                {{-- FILTER LOKASI --}}
                <div class="relative w-full lg:w-1/5 font-semibold group" x-data="{ open: false }">
                    <select 
                        id="filterLocation"
                        {{-- Saat diklik (buka menu), status open jadi true. Saat pilihan dipilih (change), status open jadi false --}}
                        @click="open = !open"
                        @blur="open = false"
                        @change="open = false"
                        class="appearance-none w-full bg-white border-none rounded-full px-6 py-4 shadow-md text-red-400 focus:outline-none focus:ring-2 focus:ring-red-700/20 cursor-pointer pr-12">
                        <option value="">Semua Lokasi</option>
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-red-700">
                        <svg 
                            {{-- Class ini yang membuat panah berputar seperti di FAQ --}}
                            :class="{ 'rotate-180': open }" 
                            class="w-5 h-5 transition-transform duration-300" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- FILTER PROFESIONAL --}}
                <div class="relative w-full lg:w-1/5 font-semibold" x-data="{ open: false }">
                    <select 
                        id="filterCategory"
                        @click="open = !open"
                        @blur="open = false"
                        @change="open = false"
                        class="appearance-none w-full bg-white border-none rounded-full px-6 py-4 shadow-md text-red-400 focus:outline-none focus:ring-2 focus:ring-red-700/20 cursor-pointer pr-12">
                        <option value="">Semua Kategori</option>
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-red-700">
                        <svg 
                            :class="{ 'rotate-180': open }" 
                            class="w-5 h-5 transition-transform duration-300" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- FILTER BIDANG --}}
                <div class="relative w-full lg:w-1/5 font-semibold" x-data="{ open: false }">
                    <select 
                        id="filterBidang"
                        @click="open = !open"
                        @blur="open = false"
                        @change="open = false"
                        class="appearance-none w-full bg-white border-none rounded-full px-6 py-4 shadow-md text-red-400 focus:outline-none focus:ring-2 focus:ring-red-700/20 cursor-pointer pr-12">
                        <option value="">Semua Bidang</option>
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-red-700">
                        <svg 
                            {{-- Class ini yang membuat panah berputar seperti di FAQ --}}
                            :class="{ 'rotate-180': open }" 
                            class="w-5 h-5 transition-transform duration-300" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

            </div>

            {{-- GRID CARD JOB POSTING / POSTINGAN LOWONGAN --}}
            <div id="jobList" class="grid md:grid-cols-2 gap-6">
                {{-- JOB CARD --}}
                @foreach ($lowongan as $item)

                    <div
                        class="job-card border border-grey-200 rounded-2xl p-6 md:p-8 bg-white shadow-xl inset-shadow-sm inset-shadow-indigo-500 hover:shadow-md transition flex flex-col gap-4"
                        data-title="{{ $item->judul_lowongan }}"
                        data-location="{{ $item->cabang?->nama }}"
                        data-category="{{ $item->kategori }}"
                        data-bidang="{{ $item->bidang }}">

                        {{-- HEADER --}}
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                            {{-- LEFT --}}
                            <div>
                                <h3 class="text-4xl font-bold text-black mb-2">
                                    {{ $item->judul_lowongan }}
                                </h3>

                                <div class="flex items-center gap-2 text-green-600 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>

                                    <span>
                                        Batas Lamar:
                                        <u>{{ \Carbon\Carbon::parse($item->tanggal_akhir)->locale('id')->translatedFormat('d F Y') }}</u>
                                    </span>
                                </div>
                            </div>

                            {{-- RIGHT --}}
                            <div class="flex flex-col gap-3 text-gray-600 mt-2 mb-5">

                                <div class="flex items-center gap-2">
                                    {{-- icon lokasi --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $item->lokasi_kerja }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    {{-- icon kategori --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path d="M8.25 10.875a2.625 2.625 0 1 1 5.25 0 2.625 2.625 0 0 1-5.25 0Z" />
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.125 4.5a4.125 4.125 0 1 0 2.338 7.524l2.007 2.006a.75.75 0 1 0 1.06-1.06l-2.006-2.007a4.125 4.125 0 0 0-3.399-6.463Z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $item->kategori }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- CONTENT --}}
                        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

                            {{-- DETAILS --}}
                            <div class="space-y-3">
                                <details>
                                    <summary class="cursor-pointer font-semibold text-black">Kualifikasi</summary>
                                    <div class="mt-2 text-gray-600 prose prose-sm max-w-none">
                                        {!! $item->kualifikasi ?: '<p class="italic text-gray-400">Kualifikasi akan diinformasikan lebih lanjut.</p>' !!}
                                    </div>
                                </details>

                                <details>
                                    <summary class="cursor-pointer font-semibold text-black">Deskripsi Pekerjaan</summary>
                                    <div class="mt-2 text-gray-600 max-w-none">
                                        {!! $item->jobdesk ?: '<p class="italic text-gray-400">Deskripsi pekerjaan akan dijelaskan saat proses seleksi.</p>' !!}
                                    </div>
                                </details>
                            </div>

                            {{-- BUTTON --}}
                            @php
                                $sudahMelamar = $applicant ? \App\Models\Application::where('applicant_id', $applicant->id)->where('lowongan_id', $item->id)->exists() : false;
                            @endphp

                            @if($sudahMelamar)
                                <button disabled class="inline-flex items-center justify-center bg-green-600 text-white text-lg font-semibold px-8 py-2 rounded-full cursor-default">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    Sudah Dilamar
                                </button>
                            @else
                                {{-- Form Lamar seperti di atas --}}
                                <div class="shrink-0">
                                    <form method="POST" action="{{ route('jobs.apply', $item->id) }}" class="form-lamar">
                                        @csrf
                                        <button type="button" 
                                                onclick="konfirmasiLamar(this)" 
                                                data-posisi="{{ $item->judul_lowongan }}"
                                                class="btn-submit-lamar inline-flex items-center justify-center bg-[#B11116] hover:bg-red-800 text-white font-semibold px-8 py-2 rounded-full transition">
                                            <span class="btn-text text-lg">Lamar</span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>

            <div id="pagination" class="flex justify-center gap-2 mt-10"></div>

        </div>
    </section>

    {{-- SCRIPT SEARCH, FILTER LOKASI, FILTER KATEGORI --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const searchInput     = document.getElementById("searchInput");
            const filterLocation  = document.getElementById("filterLocation");
            const filterBidang  = document.getElementById("filterBidang");
            const filterCategory  = document.getElementById("filterCategory");
            const jobCards        = Array.from(document.querySelectorAll(".job-card"));
            const pagination      = document.getElementById("pagination");

            const itemsPerPage = 6;
            let currentPage = 1;
            let filteredCards = [...jobCards];

            /* ===============================
            🔹 AUTO FILL DROPDOWN FILTER
            =============================== */
            function populateFilters() {
                const locations  = new Set();
                const bidangSet = new Set();
                const categories = new Set();

                jobCards.forEach(card => {
                    if (card.dataset.location) {
                        locations.add(card.dataset.location.trim());
                    }
                    if (card.dataset.bidang) {
                        bidangSet.add(card.dataset.bidang.trim());
                    }
                    if (card.dataset.category) {
                        categories.add(card.dataset.category.trim());
                    }
                });

                locations.forEach(loc => {
                    const option = document.createElement("option");
                    option.value = loc;
                    option.textContent = loc;
                    filterLocation.appendChild(option);
                });

                bidangSet.forEach(bidang => {
                    const option = document.createElement("option");
                    option.value = bidang;
                    option.textContent = bidang;
                    filterBidang.appendChild(option);
                });

                categories.forEach(cat => {
                    const option = document.createElement("option");
                    option.value = cat;
                    option.textContent = cat;
                    filterCategory.appendChild(option);
                });
            }

            /* ===============================
            🔹 FILTER LOGIC
            =============================== */
            function applyFilter() {
                const keyword  = searchInput.value.toLowerCase();
                const location = filterLocation.value;
                const bidang = filterBidang.value;
                const category = filterCategory.value;

                filteredCards = jobCards.filter(card => {
                    const title = card.dataset.title.toLowerCase();
                    const loc   = card.dataset.location;
                    const cardBidang = card.dataset.bidang;
                    const cat   = card.dataset.category;

                    const matchKeyword  = title.includes(keyword);
                    const matchLocation = location === "" || loc === location;
                    const matchBidang = bidang === "" || cardBidang === bidang;
                    const matchCategory = category === "" || cat === category;

                    return matchKeyword && matchLocation && matchBidang && matchCategory;
                });

                document.getElementById("jobCount").textContent = filteredCards.length;

                currentPage = 1;
                render();
            }

            /* ===============================
            🔹 RENDER CARD
            =============================== */
            function render() {
                jobCards.forEach(card => card.style.display = "none");

                const start = (currentPage - 1) * itemsPerPage;
                const end   = start + itemsPerPage;

                filteredCards.slice(start, end).forEach(card => {
                    card.style.display = "block";
                });

                renderPagination();
            }

            /* ===============================
            🔹 PAGINATION
            =============================== */
            function renderPagination() {
                pagination.innerHTML = "";

                const totalPages = Math.ceil(filteredCards.length / itemsPerPage);
                if (totalPages <= 1) return;

                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement("button");
                    btn.textContent = i;
                    btn.className =
                        "px-4 py-2 border rounded transition " +
                        (i === currentPage
                            ? "bg-red-700 text-white"
                            : "bg-white text-red-700 hover:bg-red-100");

                    btn.addEventListener("click", () => {
                        currentPage = i;
                        render();
                    });

                    pagination.appendChild(btn);
                }
            }

            /* ===============================
            🔹 EVENT
            =============================== */
            searchInput.addEventListener("input", applyFilter);
            filterLocation.addEventListener("change", applyFilter);
            filterBidang.addEventListener("change", applyFilter);
            filterCategory.addEventListener("change", applyFilter);

            /* ===============================
            🔹 INIT
            =============================== */
            populateFilters();
            applyFilter();

        });
    </script>

    {{-- Klik Lamar --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function konfirmasiLamar(btn) {
            const posisi = btn.getAttribute('data-posisi');
            const form = btn.closest('form');

            Swal.fire({
                title: 'Konfirmasi Lamaran',
                html: `<div class="text-gray-500 text-sm">Anda akan mengirimkan lamaran untuk posisi:</div>
                    <div class="font-bold text-gray-900 mt-1">${posisi}</div>`,
                icon: 'info',
                iconColor: '#dc2626',
                showCancelButton: true,
                confirmButtonText: 'Kirim Lamaran',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                // Desain Clean & Flat
                customClass: {
                    popup: 'rounded-2xl border-none shadow-2xl',
                    confirmButton: 'bg-red-600 text-white px-6 py-2.5 rounded-full font-bold mx-2 text-sm hover:bg-red-700 transition-colors',
                    cancelButton: 'bg-gray-100 text-gray-600 px-6 py-2.5 rounded-full font-bold mx-2 text-sm hover:bg-gray-200 transition-colors'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // LOCK LEBAR TOMBOL (Agar tidak menciut saat teks berubah)
                    btn.style.width = btn.offsetWidth + 'px';
                    btn.disabled = true;
                    
                    // UI FEEDBACK MINIMALIS
                    btn.innerHTML = `
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    `;
                    
                    form.submit();
                }
            });
        }
    </script>
    
    {{-- Script untuk menangkap session flash message --}}
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#dc2626',
            });
        @endif
    </script>
</x-app-layout>
