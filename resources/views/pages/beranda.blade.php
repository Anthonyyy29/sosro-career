<x-app-layout>

    {{-- HERO SECTION BERANDA --}}
    <section class="relative w-full h-screen overflow-hidden z-0 bg-black">
        <img src="{{ asset('assets/images/beranda.webp') }}" alt="Jadilah Bagian dari Keluarga Sosro" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-red-900 via-red/80 to-transparent"></div>
        <div class="absolute inset-0 flex flex-col md:flex-row items-center justify-center px-6 md:px-20 gap-8 max-w-7xl mx-auto">
            <div class="w-full md:w-2/3 text-center md:text-left">
                <div>
                    <h2 class="text-4xl md:text-6xl text-white font-bold uppercase leading-[1.1] md:max-w-[700px] drop-shadow-lg">
                        Tingkatkan Karir Bersama PT Sinar Sosro Gunung Slamat
                    </h2>
                    <p class="text-xl md:text-3xl text-white mt-4 md:mt-7 leading-relaxed">
                        Dapatkan informasi terbaru tentang <br>lowongan pekerjaan disini.
                    </p>
                </div>
            </div>
            <div class="w-full md:w-1/3 flex items-center justify-center md:justify-end">
                <a href="{{ route('lowongan') }}" 
                class="px-10 py-4 bg-white text-[#B51233] font-bold text-xl rounded-full 
                        shadow-[0_4px_14px_0_rgba(255,255,255,0.39)] 
                        hover:shadow-[0_6px_20px_rgba(255,255,255,0.23)] 
                        hover:bg-[#B51233] hover:text-white 
                        transition-all duration-500 ease-out
                        ring-4 ring-white/20 hover:ring-[#B51233]/30">
                    Lihat Lowongan Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION PROFIL SINGKAT --}}
    <section class="py-28 bg-gradient-to-b from-white via-[#B51233] to-white text-left relative overflow-hidden">

        {{-- Dekorasi daun --}}
        <svg class="absolute top-4 left-1/3 w-10 opacity-20 rotate-45" viewBox="0 0 60 100" xmlns="http://www.w3.org/2000/svg">
            <path d="M30 0 C10 20, 0 50, 30 100 C60 50, 50 20, 30 0Z" fill="#4caf50"/>
        </svg>
        <svg class="absolute bottom-6 left-12 w-8 opacity-20 -rotate-12" viewBox="0 0 60 100" xmlns="http://www.w3.org/2000/svg">
            <path d="M30 0 C10 20, 0 50, 30 100 C60 50, 50 20, 30 0Z" fill="#66bb6a"/>
        </svg>
        <svg class="absolute top-1/2 left-1/2 w-14 opacity-10 rotate-12" viewBox="0 0 60 100" xmlns="http://www.w3.org/2000/svg">
            <path d="M30 0 C10 20, 0 50, 30 100 C60 50, 50 20, 30 0Z" fill="#81c784"/>
        </svg>

        <div class="relative z-10 max-w-5xl mx-auto px-6">
            <div class="relative">
                
                {{-- Card Teks --}}
                <h2 class="text-3xl md:text-4xl font-bold text-[#B51233] uppercase border-b-2 border-red-700 pb-1 inline-block mb-4 drop-shadow-sm">
                    Profil Singkat
                </h2>

                <div class="relative z-10 p-8 bg-red-700 rounded-2xl shadow hover:shadow-xl transition w-full md:pr-[380px] lg:pr-[450px]">
                    <p class="text-white text-base md:text-xl leading-relaxed">
                        <span class="font-bold">PT Sinar Sosro Gunung Slamat</span>
                        yang diresmikan pada 2 Desember 2024 merupakan gabungan dari dua perusahaan.
                        PT Sinar Sosro, pelopor teh siap minum dalam kemasan botol pertama di Indonesia &amp; dunia,
                        dengan PT Gunung Slamat, perusahaan tertua di Rekso Group yang berdiri sejak 1940.
                    </p>
                </div>

                {{-- Gambar Overlay (Desktop) --}}
                {{-- Menggunakan z-20 agar di atas card, pointer-events-none agar teks tetap bisa di-select/copy --}}
                <div class="hidden md:flex absolute -top-24 -right-32 lg:-right-64 w-[700px] lg:w-[850px] z-20 pointer-events-none">
                    <img
                        src="{{ asset('assets/images/produk sosro.webp') }}"
                        alt="Produk Sosro"
                        class="w-full h-full object-contain drop-shadow-2xl"
                        style="animation: float 5s ease-in-out infinite;"
                    />
                </div>

                {{-- Gambar Overlay (Mobile) --}}
                {{-- -mt-16 membuat gambar naik menindih card merah --}}
                <div class="flex md:hidden relative z-20 -mt-16 justify-center pointer-events-none">
                    <img
                        src="{{ asset('assets/images/produk sosro.webp') }}"
                        alt="Produk Sosro"
                        class="w-[150%] max-w-none drop-shadow-2xl" 
                        style="animation: float 5s ease-in-out infinite;"
                    />
                </div>

            </div>
        </div>

        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
            }
            /* Mencegah gambar yang melebar ke kanan membuat halaman bisa di-scroll ke samping */
            section { overflow-x: hidden; }
        </style>
    </section>

    {{-- SECTION SOSIAL MEDIA --}}
    <section class="py-10 bg-white overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center gap-10">
            
            {{-- Bagian Kiri: Visual Gambar --}}
            <div class="w-full md:w-[60%] flex justify-center">
                <img 
                    src="{{ asset('assets/images/4.webp') }}" 
                    alt="Sosro Career Social Media" 
                    class="w-auto max-w-sm md:max-w-md h-auto drop-shadow-xl object-contain"
                >
            </div>

            {{-- Bagian Kanan: Teks dan Tombol --}}
            <div class="w-full md:w-[40%] text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-bold text-[#B51233] uppercase drop-shadow-sm">
                    Ikuti <br> Perjalanan <br> Karier Kami
                </h2>

                <div class="mt-10 flex flex-col gap-4 items-center md:items-start">
                    {{-- Tombol Instagram --}}
                    <a href="https://www.instagram.com/sosrocareer" target="_blank" 
                    class="flex items-center gap-3 bg-white border border-red-200 hover:border-[#B51233] hover:shadow-md transition rounded-full px-6 py-3 w-full max-w-xs">
                    {{-- class="group flex items-center gap-4 bg-white border-2 border-gray-100 px-6 py-3 rounded-full w-80 hover:shadow-lg transition duration-300 transform hover:-translate-y-1"> --}}
                    <i class="fa-brands fa-square-instagram text-4xl" style="background: -webkit-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        <span class="font-semibold text-lg text-gray-800 tracking-wide">@sosrocareer</span>
                    </a>

                    {{-- Tombol LinkedIn --}}
                    <a href="https://www.linkedin.com/company/pt-sinar-sosro" target="_blank" 
                        class="flex items-center gap-3 bg-white border border-red-200 hover:border-[#B51233] hover:shadow-md transition rounded-full px-6 py-3 w-full max-w-xs">
                        {{-- class="group flex items-center gap-4 bg-white border-2 border-gray-100 px-6 py-3 rounded-full w-80 hover:shadow-lg transition duration-300 transform hover:-translate-y-1"> --}}
                        <i class="fa-brands fa-linkedin text-4xl" style="color: #0077B5;"></i>
                        <span class="font-semibold text-base text-gray-800 leading-tight">
                            PT Sinar Sosro Gunung Slamat</span>
                        </span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    {{-- SECTION AJAKAN BERGABUNG --}}
    <section class="py-20 bg-[#B51233] px-6">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold uppercase text-white mb-4 drop-shadow-lg">
                TEMUKAN KESEMPATAN BERTUMBUH & BERKEMBANG BERSAMA KAMI
            </h2>
            <p class="text-lg md:text-xl text-red-100 mt-4">
                PT Sinar Sosro Gunung Slamat berkomitmen mengembangkan potensi karyawan melalui pelatihan berkelanjutan, 
                jalur karier terstruktur, dan keterlibatan dalam proyek strategis untuk mendukung pertumbuhan profesional bersama perusahaan.
            </p>

            <a href="{{ route('program') }}" 
                class="inline-block bg-white text-[#B51233] px-10 py-4 rounded-full font-bold text-lg mt-8 
                        shadow-lg shadow-black/20 hover:shadow-white/20 
                        transform transition-all duration-300 
                        hover:-translate-y-1 hover:scale-105 active:scale-95">
                Lihat Selengkapnya
            </a>
        </div>
    </section>

    {{-- SECTION NILAI PERUSAHAAN (BANNER CAROUSEL) --}}
    <section class="py-24 bg-white overflow-hidden">
        <div class="flex flex-col lg:flex-row items-center relative">
            
            {{-- BAGIAN KIRI: TEKS (Sebagai Penghalang/Masking) --}}
            <div class="w-full lg:w-[40%] px-6 lg:pl-16 lg:pr-12 text-center lg:text-left mb-12 lg:mb-0 z-20 bg-white self-stretch flex flex-col justify-center">
                <h2 class="text-3xl md:text-4xl font-bold text-[#B51233] uppercase mb-5">
                    Nilai Perusahaan
                </h2>
                <p class="text-lg md:text-xl text-[#B51233] font-medium md:max-w-md mx-auto lg:mx-0">
                    Pedoman dalam membentuk sikap, perilaku, dan cara kerja insan perusahaan serta dasar setiap pengambilan keputusan dan interaksi kerja.
                </p>
            </div>

            {{-- BAGIAN KANAN: SLIDER (Menembus ke Kanan) --}}
            <div class="w-full lg:w-[60%] relative z-10">
                {{-- Kontainer Merah --}}
                <div class="bg-[#B51233] rounded-l-[50px] lg:rounded-r-none p-10 pt-16 lg:pl-16 relative">
                    
                    {{-- Swiper Container TANPA overflow-hidden --}}
                    <div class="swiper-container-nilai !overflow-visible"> 
                        <div class="swiper-wrapper">
                            {{-- Banner 1 --}}
                            <div class="swiper-slide !h-auto">
                                <div class="aspect-square bg-white rounded-3xl shadow-2xl overflow-hidden group">
                                    <img src="{{ asset('assets/images/banner niat baik.webp') }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                </div>
                            </div>
                            {{-- Banner 2 --}}
                            <div class="swiper-slide !h-auto">
                                <div class="aspect-square bg-white rounded-3xl shadow-2xl overflow-hidden group">
                                    <img src="{{ asset('assets/images/banner acc.webp') }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                </div>
                            </div>
                            {{-- Banner 3 --}}
                            <div class="swiper-slide !h-auto">
                                <div class="aspect-square bg-white rounded-3xl shadow-2xl overflow-hidden group">
                                    <img src="{{ asset('assets/images/banner teruji.webp') }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                </div>
                            </div>
                            {{-- Banner 4 --}}
                            <div class="swiper-slide !h-auto">
                                <div class="aspect-square bg-white rounded-3xl shadow-2xl overflow-hidden group">
                                    <img src="{{ asset('assets/images/banner 3h.webp') }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div class="swiper-pagination-nilai mt-10 flex justify-center lg:justify-start"></div>
                    
                    {{-- Dekorasi Garis --}}
                    {{-- <div class="absolute inset-y-10 left-10 right-0 border-2 border-white/20 border-dashed rounded-l-[40px] pointer-events-none"></div> --}}
                </div>
            </div>
        </div>
    </section>

    {{-- Inisialisasi JavaScript Swiper --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiperNilai = new Swiper('.swiper-container-nilai', {
                // SlidesPerView 'auto' atau desimal agar gambar paling kanan terlihat terpotong
                slidesPerView: 1.2, 
                spaceBetween: 20,
                centeredSlides: false,
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination-nilai',
                    clickable: true,
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2.2,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 2.5, // Menunjukkan 2 full banner dan setengah banner berikutnya
                        spaceBetween: 40,
                    },
                },
            });
        });
    </script>

    <style>
        /* Styling Pagination agar warna putih dan berbentuk pill saat aktif */
        .swiper-pagination-nilai .swiper-pagination-bullet {
            background: white;
            opacity: 0.5;
        }
        .swiper-pagination-nilai .swiper-pagination-bullet-active {
            opacity: 1;
            width: 24px;
            border-radius: 12px;
            transition: all 0.3s;
        }
    </style>

    {{-- SECTION KEGIATAN PERUSAHAAN --}}
    <section class="py-15 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- Judul Section --}}
            <h2 class="text-3xl md:text-4xl font-bold text-[#B51233] uppercase mb-10 text-center lg:text-left drop-shadow-sm">
                Kegiatan Perusahaan
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 overflow-hidden rounded-3xl shadow-xl">
                
                @php
                    $kegiatan = [
                        ['img' => 'kegiatan sosial sosro.png', 'title' => 'Program <br> Guru Magang'],
                        ['img' => 'training sosro.webp', 'title' => 'Kunjungan <br> Industri'],
                        ['img' => 'kegiatan sosial sosro.webp', 'title' => 'Pelatihan <br> Internal'],
                        ['img' => 'training sosro.webp', 'title' => 'Corporate <br> Social Responsibility'],
                        ['img' => 'kegiatan sosial sosro.webp', 'title' => 'Employee <br> Gathering'],
                        ['img' => 'training sosro.webp', 'title' => 'Project <br> Strategis'],
                        // ['img' => 'kegiatan-6.webp', 'title' => 'Project <br> Strategis'],
                    ];
                @endphp

                @foreach($kegiatan as $item)
                <div class="relative group overflow-hidden aspect-[4/3] cursor-pointer">
                    <img src="{{ asset('assets/images/' . $item['img']) }}" 
                        alt="{{ strip_tags($item['title']) }}" 
                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700 ease-in-out">
                    
                    {{-- Overlay: Default opacity-0, muncul saat group-hover --}}
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-500 ease-in-out backdrop-blur-sm">
                        <h3 class="text-white text-xl md:text-2xl font-bold uppercase tracking-wider text-center px-6 transform translate-y-4 group-hover:translate-y-0 transition duration-500 ease-in-out">
                            {!! $item['title'] !!}
                        </h3>
                    </div>
                </div>
                @endforeach

            </div>

            {{-- Tombol Lihat Selengkapnya --}}
            <div class="mt-12 text-center">
                <a href="{{ route('tentang') }}#kegiatan-perusahaan" 
                class="inline-block bg-[#B51233] text-white px-10 py-3 rounded-full font-bold text-lg hover:bg-red-800 transition shadow-sm transform hover:-translate-y-1 active:scale-95">
                    Lihat Selengkapnya
                </a>
            </div>

        </div>
    </section>

    {{-- SECTION TESTIMONI KARYAWAN --}}
    <section class="py-16 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            
            <h2 class="text-3xl md:text-4xl font-bold text-[#B51233] uppercase mb-5 text-center drop-shadow-sm">
                Apa Kata Karyawan Kami?
            </h2>

            <div class="relative px-0 md:px-12">
                
                <div class="swiper swiper-testimoni">
                    <div class="swiper-wrapper">

                        @php
                            // Data Karyawan (Pastikan path file sesuai dengan folder public/assets/images Anda)
                            $testimoni = [
                                [
                                    'img' => 'karyawan-1.webp',
                                    'name' => 'April Riya Enziliana',
                                    'role' => 'Product Portfolio',
                                    'quote' => '“Lingkungan kerja yang produktif, dimana atasan dan rekan kerja sangat mensupport terhadap perkembangan kemampuan kinerja saya serta komunikasi yang terjalin dengan sangat baik.”'
                                ],
                                [
                                    'img' => 'karyawan-2.webp',
                                    'name' => 'Adeline Chrisatmaja',
                                    'role' => 'Accounting',
                                    'quote' => '“Bangga menjadi bagian dari PT Sinar Sosro Gunung Slamat (SGS) dimana PT Sinar Sosro Gunung Slamat salah satu perusahaan pelopor teh nomor satu di Indonesia.”'
                                ],
                                [
                                    'img' => 'karyawan-3.webp',
                                    'name' => 'Gio Fandi',
                                    'role' => 'Food Service',
                                    'quote' => '“Bergabung di SGS dengan filosofi "Niat Baik Hasil Baik" membantu saya menerapkan prinsip ini di dunia FMCG. Budaya Kekeluargaan di SGS juga memudahkan adaptasi di lingkungan yang baru.”'
                                ],
                                [
                                    'img' => 'karyawan-4.webp',
                                    'name' => 'Derian Felix',
                                    'role' => 'Brand Marketing',
                                    'quote' => '“Budaya kebersamaan yang menjunjung tinggi Niat Baik Hasil Baik yang menjadi landasan utama dalam etos bekerja dan berkarya di PT Sinar Sosro Gunung Slamat.”'
                                ],
                            ];
                        @endphp

                        @foreach($testimoni as $item)
                        <div class="swiper-slide pt-10 pb-1">
                            <div class="bg-[#B51233] rounded-[40px] relative flex flex-col md:flex-row items-center min-h-[480px] md:min-h-[400px]">
                                
                                <div class="w-full md:w-1/3 flex justify-center md:justify-start items-end relative z-10 h-[280px] md:h-[450px]">
                                    <img src="{{ asset('assets/images/' . $item['img']) }}" 
                                        alt="{{ $item['name'] }}" 
                                        class="w-auto h-full max-w-full object-contain object-bottom md:absolute md:-bottom-2 md:left-6 lg:left-10">
                                </div>

                                <div class="w-full md:w-2/3 p-6 md:pr-10 md:pl-4 relative z-0">
                                    <div class="bg-white rounded-[30px] p-6 md:p-10 shadow-xl min-h-[320px] flex flex-col justify-center">
                                        <p class="text-[#B51233] text-base md:text-xl lg:text-2xl font-medium leading-relaxed italic">
                                            {{ $item['quote'] }}
                                        </p>
                                        <div class="mt-6">
                                            <h4 class="text-[#B51233] text-lg md:text-xl font-bold">{{ $item['name'] }}</h4>
                                            <p class="text-red-400 text-sm md:text-base italic">{{ $item['role'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>

                    <div class="swiper-pagination-testimoni mt-8 flex justify-center"></div>
                </div>

                <button class="nav-prev hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-30 bg-white text-[#B51233] w-11 h-11 rounded-full shadow-lg items-center justify-center hover:bg-red-50 transition border border-gray-100">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="nav-next hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-30 bg-white text-[#B51233] w-11 h-11 rounded-full shadow-lg items-center justify-center hover:bg-red-50 transition border border-gray-100">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

            </div>
        </div>
    </section>

    <style>
        /* Styling Pagination Agar Sesuai Tema */
        .swiper-pagination-testimoni .swiper-pagination-bullet {
            background: #B51233;
            opacity: 0.2;
            width: 10px;
            height: 10px;
        }
        .swiper-pagination-testimoni .swiper-pagination-bullet-active {
            opacity: 1;
            width: 30px;
            border-radius: 12px;
            transition: all 0.4s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiperTesti = new Swiper('.swiper-testimoni', {
                loop: true,
                grabCursor: true,
                spaceBetween: 40,
                autoplay: {
                    delay: 6500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination-testimoni',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.nav-next',
                    prevEl: '.nav-prev',
                },
                breakpoints: {
                    // Saat layar kecil, animasi transisi disesuaikan
                    320: {
                        spaceBetween: 20
                    },
                    768: {
                        spaceBetween: 40
                    }
                }
            });
        });
    </script>

</x-app-layout>