<x-app-layout>

    {{-- ALERT HATI-HATI PENIPUAN --}}
    <div 
        x-data="{ open: true }"
        x-show="open"
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex justify-center items-center z-50"
    >
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

    {{-- SECTION LOWONGAN --}}
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">

            {{-- JUDUL --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-red-700 mb-4">Lowongan Karier Terbaru</h1>
                <p class="text-gray-700 text-lg">
                    Temukan posisi yang sesuai dengan keahlian dan minat kamu.
                </p>
            </div>

            {{-- SEARCH + FILTER --}}
            <div class="flex flex-col md:flex-row gap-4 justify-center mb-10">

                {{-- INPUT SEARCH --}}
                <input 
                    id="searchInput"
                    type="text" 
                    placeholder="🔍 Cari posisi…"
                    class="w-full md:w-1/2 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-700"
                />

                {{-- FILTER LOKASI --}}
                <select 
                    id="filterLocation"
                    class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-700"
                >
                    <option value="">Semua Lokasi</option>
                    <option value="Jakarta Timur">Jakarta Timur</option>
                    <option value="Cikarang">Cikarang</option>
                    <option value="Bali">Bali</option>
                    <option value="Pangkal Pinang">Pangkal Pinang</option>
                </select>

                {{-- FILTER BIDANG PEKERJAAN --}}
                <select 
                    id="filterCategory"
                    class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-700"
                >
                    <option value="">Semua Bidang</option>
                    <option value="IT">IT</option>
                    <option value="HC">Human Capital</option>
                    <option value="Sales Operation">Sales Operation</option>
                </select>
            </div>

            {{-- GRID JOB LIST --}}
            <div id="jobList" class="grid md:grid-cols-2 gap-6">
                {{-- JOB CARD --}}
                @foreach ($lowongan as $item)

                    <div
                        class="job-card border border-red-600 rounded-2xl p-6 md:p-8 bg-white shadow-sm hover:shadow-md transition flex flex-col gap-4"
                        data-title="{{ $item->judul_lowongan }}"
                        data-location="{{ $item->penempatan_cabang }}"
                        data-category="{{ $item->kategori }}">

                        {{-- HEADER --}}
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                            {{-- LEFT --}}
                            <div>
                                <h3 class="text-3xl font-extrabold text-black mb-2">
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
                                    <span>{{ $item->penempatan_cabang }}</span>
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
                                    <summary class="cursor-pointer font-semibold text-black">
                                        Kualifikasi
                                    </summary>

                                    @if(!empty($item->kualifikasi))
                                        <ul class="list-disc ml-5 mt-2 text-gray-600 space-y-1">
                                            @foreach (explode("\n", $item->kualifikasi) as $row)
                                                @if(trim($row) !== '')
                                                    <li>{{ $row }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-2 text-gray-400 italic">
                                            Kualifikasi akan diinformasikan lebih lanjut.
                                        </p>
                                    @endif
                                </details>

                                <details>
                                    <summary class="cursor-pointer font-semibold text-black">
                                        Jobdesk
                                    </summary>

                                    @if(!empty($item->jobdesk))
                                        <ul class="list-disc ml-5 mt-2 text-gray-600 space-y-1">
                                            @foreach (explode("\n", $item->jobdesk) as $row)
                                                @if(trim($row) !== '')
                                                    <li>{{ $row }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-2 text-gray-400 italic">
                                            Deskripsi pekerjaan akan dijelaskan saat proses seleksi.
                                        </p>
                                    @endif
                                </details>
                            </div>

                            {{-- BUTTON --}}
                            <div class="shrink-0">
                                <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center bg-red-600 text-white font-semibold px-8 py-3 rounded-full hover:bg-red-700 transition">
                                    Lamar
                                </a>
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            <div id="pagination" class="flex justify-center gap-2 mt-10"></div>

        </div>
    </section>

    {{-- SCRIPT SEARCH, FILTER LOKASI, FILTER BIDANG --}}
    <script>
        const searchInput = document.getElementById("searchInput");
        const filterLocation = document.getElementById("filterLocation");
        const filterCategory = document.getElementById("filterCategory");
        const jobCards = document.querySelectorAll(".job-card");

        function filterJobs() {
            const keyword = searchInput.value.toLowerCase();
            const location = filterLocation.value;
            const category = filterCategory.value;

            jobCards.forEach(card => {
                const title = card.dataset.title.toLowerCase();
                const loc = card.dataset.location;
                const cat = card.dataset.category;

                const matchKeyword = title.includes(keyword);
                const matchLocation = location === "" || loc === location;
                const matchCategory = category === "" || cat === category;

                card.style.display = matchKeyword && matchLocation && matchCategory 
                    ? "block" 
                    : "none";
            });

            setupPagination();
        }

        searchInput.addEventListener("input", filterJobs);
        filterLocation.addEventListener("change", filterJobs);
        filterCategory.addEventListener("change", filterJobs);
        const jobList = document.getElementById("jobList");
        const pagination = document.getElementById("pagination");
        const itemsPerPage = 6;  // jumlah card per halaman
        let currentPage = 1;
        
        function setupPagination() {
            const visibleCards = [...jobCards].filter(card => card.style.display !== "none");
            const totalPages = Math.ceil(visibleCards.length / itemsPerPage);
        
            visibleCards.forEach((card, index) => {
                card.style.display =
                    index >= (currentPage - 1) * itemsPerPage &&
                    index < currentPage * itemsPerPage
                        ? "block"
                        : "none";
            });
        
            renderPagination(totalPages);
        }
        
        function renderPagination(totalPages) {
            pagination.innerHTML = "";
        
            for (let page = 1; page <= totalPages; page++) {
                const btn = document.createElement("button");
                btn.textContent = page;
                btn.className =
                    "px-4 py-2 border rounded " +
                    (page === currentPage
                        ? "bg-red-700 text-white"
                        : "bg-white text-red-700");
        
                btn.addEventListener("click", () => {
                    currentPage = page;
                    setupPagination();
                });
        
                pagination.appendChild(btn);
            }
        }
        
        // initialize
        setupPagination();
    </script>
</x-app-layout>
