<x-app-layout>

    {{-- HERO SECTION TENTANG --}}
    <section class="relative w-full h-screen overflow-hidden z-0 bg-black">
        <img src="{{ asset('assets/images/tentang-hero.webp') }}" alt="Intip Keseruan Insan Sinar Sosro Gunung Slamat" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-red-900 via-red/80 to-transparent"></div>
        <div class="absolute inset-0 flex items-center justify-center px-6 md:px-20 max-w-7xl mx-auto">
            <div class="w-full text-center md:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl text-white font-bold uppercase leading-[1.1] md:max-w-[700px] drop-shadow-lg">
                    Intip Keseruan Insan Sinar Sosro Gunung Slamat
                </h1>
                <p class="text-lg md:text-3xl text-white mt-6 md:mt-8 leading-relaxed max-w-2xl">
                    Berdasarkan ambisi sebagai <i>The Good Beverage Company</i>, Sinar Sosro Gunung Slamat membuka peluang untuk tumbuh dan berkembang bersama para ahlinya.
                </p>
            </div>
        </div>
    </section>

    {{-- SECTION JENJANG KARIR --}}
    <section class="py-20 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- Bagian Judul: Rata Kiri Atas --}}
            <div class="mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-[#B11116] uppercase mb-5 drop-shadow-sm">
                    JENJANG <br> KARIER
                </h2>
            </div>

            {{-- Visualisasi Tangga: Berada di Bawah Judul --}}
            <div class="relative w-full">
                    <div class="hidden md:block absolute top-[-8px] left-[12%] w-[300px] rotate-12 pointer-events-none z-30">
                        <img src="{{ asset('assets/images/30.png') }}" 
                             alt="Dekorasi Karir" 
                             class="w-full h-auto drop-shadow-xl animate-bounce-slow">
                    </div>
                {{-- Grid Tangga --}}
                {{-- items-end memastikan kotak tumbuh dari bawah ke atas --}}
                <div class="flex flex-col md:flex-row items-end gap-1 md:gap-2 relative z-10">
                    
                    {{-- Entry Level --}}
                    <div class="w-full md:w-1/4 group">
                        <div class="bg-[#B11116] group-hover:bg-white border-2 border-[#B11116] rounded-l-2xl rounded-tr-2xl p-6 h-32 md:h-44 
                            flex items-center justify-center shadow-md transition-all duration-500 ease-in-out transform group-hover:-translate-y-2">
                            <span class="text-white group-hover:text-[#B11116] font-bold text-xl md:text-2xl text-center transition-colors duration-500">
                                Entry Level
                            </span>
                        </div>
                    </div>

                    {{-- Associate --}}
                    <div class="w-full md:w-1/4 group">
                        <div class="bg-[#B11116] group-hover:bg-white border-2 border-[#B11116]  rounded-l-2xl rounded-tr-2xl p-6 h-40 md:h-64 
                            flex items-center justify-center shadow-md transition-all duration-500 ease-in-out transform group-hover:-translate-y-2">
                            <span class="text-white group-hover:text-[#B11116] font-bold text-xl md:text-2xl text-center transition-colors duration-500">
                                Associate
                            </span>
                        </div>
                    </div>

                    {{-- Mid Level --}}
                    <div class="w-full md:w-1/4 group">
                        <div class="bg-[#B11116] group-hover:bg-white border-2 border-[#B11116]  rounded-l-2xl rounded-tr-2xl p-6 h-48 md:h-80 
                            flex items-center justify-center shadow-md transition-all duration-500 ease-in-out transform group-hover:-translate-y-2">
                            <span class="text-white group-hover:text-[#B11116] font-bold text-xl md:text-2xl text-center transition-colors duration-500">
                                Mid Level
                            </span>
                        </div>
                    </div>

                    {{-- Senior --}}
                    <div class="w-full md:w-1/4 group">
                        <div class="bg-[#B11116] group-hover:bg-white border-2 border-[#B11116] rounded-l-2xl rounded-tr-2xl p-6 h-56 md:h-[400px] 
                            flex items-center justify-center shadow-md transition-all duration-500 ease-in-out transform group-hover:-translate-y-2">
                            <span class="text-white group-hover:text-[#B11116] font-bold text-xl md:text-2xl text-center transition-colors duration-500">
                                Senior
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: PROSES REKRUTMEN --}}
    <section class="py-20 bg-[#B11116] relative overflow-hidden text-white">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
                <h2 class="text-4xl md:text-5xl font-bold uppercase"> PROSES <br><span class="text-2xl md:text-3xl">REKRUTMEN</span> </h2>
                <div class="flex-1 h-3 bg-white rounded-full mx-12 self-center hidden md:block"></div>
                <div id="rekrutmen-status-label" class="bg-white text-[#B11116] px-8 py-2 rounded-full font-bold text-lg shadow-xl uppercase transition-all duration-300"> PRO-HIRE </div>
            </div>

            {{-- Swiper Container --}}
            <div class="relative swiper swiper-rekrutmen !overflow-visible">
                <div class="swiper-wrapper">

                    {{-- SLIDE 1: PRO-HIRE --}}
                    <div class="swiper-slide py-4">
                        <div class="relative flex flex-col items-center">
                            {{-- Line Tengah --}}
                            <div class="absolute left-1/2 -translate-x-1/2 h-full w-1 bg-white z-0"></div>
                            
                            <div class="w-full relative z-10 space-y-8 md:space-y-4">
                                @php
                                    $proHire = [
                                        ['t' => 'Seleksi CV', 'd' => 'Penilaian kesesuaian kualifikasi kandidat dengan posisi yang dilamar.'],
                                        ['t' => 'Psikotes', 'd' => 'Pengukuran aspek psikologis dan kemampuan kognitif kandidat.'],
                                        ['t' => 'Interview', 'd' => 'Pendalaman kompetensi, motivasi, dan kesesuaian kandidat.'],
                                        ['t' => 'MCU', 'd' => 'Medical Check-Up untuk memastikan kesiapan fisik kerja.'],
                                        ['t' => 'On-Boarding', 'd' => 'Proses pengenalan dan penyesuaian karyawan baru dengan perusahaan.'],
                                    ];
                                @endphp

                                @foreach($proHire as $index => $item)
                                <div class="flex items-center w-full">
                                    {{-- Kiri --}}
                                    <div class="w-1/2 flex justify-end pr-6 md:pr-10">
                                        @if($index % 2 == 0)
                                            <div class="text-right max-w-xs">
                                                <h3 class="text-xl md:text-2xl font-bold uppercase">{{ $item['t'] }}</h3>
                                                <p class="text-sm md:text-base text-white/80 leading-snug">{{ $item['d'] }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Dot/Nomor --}}
                                    <div class="relative z-20 flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-white text-[#B11116] rounded-full flex items-center justify-center font-black text-2xl">
                                    {{-- <div class="relative z-20 flex-shrink-0 w-6 h-6 md:w-8 md:h-8 bg-white text-white rounded-full flex items-center justify-center font-black text-2xl"> --}}
                                        {{ $index + 1 }}
                                    </div>

                                    {{-- Kanan --}}
                                    <div class="w-1/2 flex justify-start pl-6 md:pl-10">
                                        @if($index % 2 != 0)
                                            <div class="text-left max-w-xs">
                                                <h3 class="text-xl md:text-2xl font-bold uppercase">{{ $item['t'] }}</h3>
                                                <p class="text-sm md:text-base text-white/80 leading-snug">{{ $item['d'] }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- SLIDE 2: MANAGEMENT TRAINEE --}}
                    <div class="swiper-slide py-4">
                        <div class="relative flex flex-col items-center">
                            <div class="absolute left-1/2 -translate-x-1/2 h-full w-1 bg-white z-0"></div>
                            
                            <div class="w-full relative z-10 space-y-6 md:space-y-2">
                                @php
                                    $mtSteps = [
                                        ['t' => 'Seleksi CV', 'd' => 'Penilaian kesesuaian kualifikasi kandidat dengan posisi yang dilamar.'],
                                        ['t' => 'Form Biodata', 'd' => 'Pengumpulan data diri sebagai kelengkapan administrasi.'],
                                        ['t' => 'Psikotes', 'd' => 'Pengukuran aspek psikologis dan kemampuan kognitif kandidat.'],
                                        ['t' => 'Interview', 'd' => 'Pendalaman kompetensi, motivasi, dan kesesuaian kandidat.'],
                                        ['t' => 'Study Case', 'd' => 'Evaluasi kemampuan analisis dan pemecahan masalah.'],
                                        ['t' => 'MCU', 'd' => 'Medical Check-Up, pemeriksaan kesehatan untuk memastikan kesiapan kerja.'],
                                        ['t' => 'On-Boarding', 'd' => 'Proses pengenalan dan penyesuaian karyawan baru dengan perusahaan.'],
                                    ];
                                @endphp

                                @foreach($mtSteps as $index => $item)
                                <div class="flex items-center w-full">
                                    <div class="w-1/2 flex justify-end pr-6 md:pr-10">
                                        @if($index % 2 == 0)
                                            <div class="text-right max-w-xs">
                                                <h3 class="text-xl md:text-2xl font-bold uppercase">{{ $item['t'] }}</h3>
                                                <p class="text-xs md:text-sm text-white/80 leading-tight">{{ $item['d'] }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="relative z-20 flex-shrink-0 w-10 h-10 md:w-12 md:h-12 bg-white text-[#B11116] rounded-full flex items-center justify-center font-black text-2xl">
                                    {{-- <div class="relative z-20 flex-shrink-0 w-5 h-5 md:w-7 md:h-7 bg-white text-white rounded-full flex items-center justify-center font-black text-2xl"> --}}
                                        {{ $index + 1 }}
                                    </div>

                                    <div class="w-1/2 flex justify-start pl-6 md:pl-10">
                                        @if($index % 2 != 0)
                                            <div class="text-left max-w-xs">
                                                <h3 class="text-xl md:text-2xl font-bold uppercase">{{ $item['t'] }}</h3>
                                                <p class="text-xs md:text-sm text-white/80 leading-tight">{{ $item['d'] }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Navigation --}}
                <div class="swiper-rekrutmen-prev absolute left-0 md:-left-12 top-1/2 -translate-y-1/2 z-40 cursor-pointer text-white/50 hover:text-white transition-all">
                    <i class="fa-solid fa-chevron-left text-4xl md:text-6xl"></i>
                </div>
                <div class="swiper-rekrutmen-next absolute right-0 md:-right-12 top-1/2 -translate-y-1/2 z-40 cursor-pointer text-white/50 hover:text-white transition-all">
                    <i class="fa-solid fa-chevron-right text-4xl md:text-6xl"></i>
                </div>

                {{-- Bullet Pagination --}}
                <div class="swiper-pagination-rekrutmen mt-12 flex justify-center gap-2"></div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labelStatus = document.getElementById('rekrutmen-status-label');
            
            new Swiper('.swiper-rekrutmen', {
                slidesPerView: 1,
                autoHeight: true,
                grabCursor: true,
                navigation: {
                    nextEl: '.swiper-rekrutmen-next',
                    prevEl: '.swiper-rekrutmen-prev',
                },
                pagination: {
                    el: '.swiper-pagination-rekrutmen',
                    clickable: true,
                    renderBullet: function (index, className) {
                        return `<span class="${className} custom-bullet"></span>`;
                    },
                },
                on: {
                    slideChange: function () {
                        labelStatus.innerText = this.activeIndex === 0 ? 'PRO-HIRE' : 'MANAGEMENT TRAINEE';
                    }
                }
            });
        });
    </script>

    <style>
        /* Styling Bullets agar lebih modern */
        .custom-bullet {
            width: 12px !important;
            height: 12px !important;
            background: white !important;
            opacity: 0.4 !important;
            border-radius: 99px !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .swiper-pagination-bullet-active {
            width: 40px !important;
            opacity: 1 !important;
            background: white !important;
        }
        /* Memadatkan jarak agar tidak terlalu jauh */
        .swiper-slide {
            height: auto !important;
        }
    </style>

    {{-- SECTION: DEPARTEMEN PERUSAHAAN --}}
    <section class="py-20 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- Judul Section --}}
            <h2 class="text-3xl md:text-4xl font-bold text-[#B11116] text-center uppercase mb-12 drop-shadow-sm">
                DEPARTEMEN PERUSAHAAN
            </h2>

            {{-- Swiper Container --}}
            <div class="relative swiper swiper-departemen">
                <div class="swiper-wrapper">
                    
                    @php
                        $departemen = [
                            ['name' => 'Human Capital', 'img' => 'karyawan-1.webp'],
                            ['name' => 'Supply Chain Management', 'img' => 'karyawan-2.webp'],
                            ['name' => 'International', 'img' => 'karyawan-3.webp'],
                            ['name' => 'Sales & Distribution', 'img' => 'karyawan-4.webp'],
                            ['name' => 'Marketing', 'img' => 'karyawan-1.webp'],
                            ['name' => 'Finance & Accounting', 'img' => 'karyawan-2.webp'],
                            ['name' => 'Production', 'img' => 'karyawan-3.webp'],
                            ['name' => 'Quality Control', 'img' => 'karyawan-4.webp'],
                            ['name' => 'Research & Development', 'img' => 'karyawan-2.webp'],
                        ];
                    @endphp

                    @foreach($departemen as $dept)
                    <div class="swiper-slide h-auto">
                        {{-- Card Container --}}
                        <div class="relative bg-[#B11116] rounded-[2rem] overflow-hidden flex flex-col items-center pt-6 transition-transform duration-300 hover:scale-105 group h-full">
                            
                            {{-- Foto Karyawan (Model) --}}
                            <div class="relative z-10 w-full px-4 overflow-hidden">
                                <img src="{{ asset('assets/images/' . $dept['img']) }}" alt="{{ $dept['name'] }}" 
                                    class="w-full h-64 md:h-80 object-contain object-bottom transform transition-transform duration-500 group-hover:scale-110">
                            </div>

                            {{-- Title Nama Departemen (Menyatu di Bawah) --}}
                            <div class="w-full bg-white p-4 mt-auto">
                                <h3 class="text-[#B11116] text-center font-semibold text-sm md:text-lg min-h-[3rem] flex items-center justify-center">
                                    {{ $dept['name'] }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Custom Navigation --}}
                {{-- <div class="swiper-departemen-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 cursor-pointer bg-white/80 p-2 rounded-full shadow-lg text-[#B11116] hover:bg-[#B11116] hover:text-white transition-all -ml-4 md:-ml-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <div class="swiper-departemen-next absolute right-0 top-1/2 -translate-y-1/2 z-20 cursor-pointer bg-white/80 p-2 rounded-full shadow-lg text-[#B11116] hover:bg-[#B11116] hover:text-white transition-all -mr-4 md:-mr-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                    </svg>
                </div> --}}
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.swiper-departemen', {
                slidesPerView: 1.5, // Muncul sebagian untuk efek carousel
                spaceBetween: 20,
                centeredSlides: false,
                grabCursor: true,
                loop: true,
                navigation: {
                    nextEl: '.swiper-departemen-next',
                    prevEl: '.swiper-departemen-prev',
                },
                breakpoints: {
                    // Desktop
                    1024: {
                        slidesPerView: 5,
                        spaceBetween: 25,
                    },
                    // Tablet
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    }
                }
            });
        });
    </script>

    <style>
        /* Tambahan agar tinggi card seragam walau teks berbeda panjang */
        .swiper-departemen .swiper-slide {
            display: flex;
            height: auto;
        }
    </style>

    {{-- SECTION: JENJANG KARIER (OLD) --}}
    {{-- <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-red-700 text-center mb-12">Jenjang Karier</h2>

            <div class="grid md:grid-cols-3 gap-10">
                
                <div class="p-8 rounded-2xl shadow-lg border hover:shadow-xl transition bg-white">
                    <div class="text-red-700 text-4xl mb-4">🔰</div>
                    <h3 class="text-xl font-semibold mb-3">Entry Level</h3>
                    <p class="text-gray-600">
                        Tahap awal bagi talenta muda untuk memulai perjalanan karier di lingkungan kerja yang suportif.
                    </p>
                </div>

                <div class="p-8 rounded-2xl shadow-lg border hover:shadow-xl transition bg-white">
                    <div class="text-red-700 text-4xl mb-4">📈</div>
                    <h3 class="text-xl font-semibold mb-3">Middle Level</h3>
                    <p class="text-gray-600">
                        Pengembangan kemampuan kepemimpinan dan pengelolaan tim menuju tanggung jawab yang lebih besar.
                    </p>
                </div>

                <div class="p-8 rounded-2xl shadow-lg border hover:shadow-xl transition bg-white">
                    <div class="text-red-700 text-4xl mb-4">🏆</div>
                    <h3 class="text-xl font-semibold mb-3">Senior & Leader</h3>
                    <p class="text-gray-600">
                        Pada tahapan ini, karyawan berperan sebagai pengambil keputusan dan penggerak utama perusahaan.
                    </p>
                </div>

            </div>
        </div>
    </section> --}}

    {{-- SECTION: PROSES REKRUTMEN (OLD) --}}
    {{-- <section class="py-20 bg-gradient-to-b from-white to-red-50">
        <div class="max-w-5xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-red-700 text-center mb-12">Proses Rekrutmen</h2>

            <div class="space-y-10 relative">

                <!-- Garis Tengah -->
                <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-red-300"></div>

                <!-- 1 -->
                <div class="relative flex items-center">
                    <div class="w-1/2 pr-10 text-right">
                        <h3 class="text-xl font-semibold">1. Kirim Lamaran</h3>
                        <p class="text-gray-600">Pelamar mengisi data dan mengunggah berkas secara online.</p>
                    </div>
                    <div class="w-10 h-10 bg-red-600 rounded-full z-10 mx-auto"></div>
                    <div class="w-1/2"></div>
                </div>

                <!-- 2 -->
                <div class="relative flex items-center">
                    <div class="w-1/2"></div>
                    <div class="w-10 h-10 bg-red-600 rounded-full z-10 mx-auto"></div>
                    <div class="w-1/2 pl-10 text-left">
                        <h3 class="text-xl font-semibold">2. Seleksi Administrasi</h3>
                        <p class="text-gray-600">Tim HR menilai kesesuaian berkas dengan kebutuhan posisi.</p>
                    </div>
                </div>

                <!-- 3 -->
                <div class="relative flex items-center">
                    <div class="w-1/2 pr-10 text-right">
                        <h3 class="text-xl font-semibold">3. Tes Kompetensi</h3>
                        <p class="text-gray-600">Tes kemampuan, psikologi, atau teknis sesuai bidang.</p>
                    </div>
                    <div class="w-10 h-10 bg-red-600 rounded-full z-10 mx-auto"></div>
                    <div class="w-1/2"></div>
                </div>

                <!-- 4 -->
                <div class="relative flex items-center">
                    <div class="w-1/2"></div>
                    <div class="w-10 h-10 bg-red-600 rounded-full z-10 mx-auto"></div>
                    <div class="w-1/2 pl-10 text-left">
                        <h3 class="text-xl font-semibold">4. Interview</h3>
                        <p class="text-gray-600">Pelamar melakukan wawancara dengan HR atau user terkait.</p>
                    </div>
                </div>

                <!-- 5 -->
                <div class="relative flex items-center">
                    <div class="w-1/2 pr-10 text-right">
                        <h3 class="text-xl font-semibold">5. Penerimaan</h3>
                        <p class="text-gray-600">Pelamar yang lolos akan mendapatkan surat penawaran kerja.</p>
                    </div>
                    <div class="w-10 h-10 bg-red-600 rounded-full z-10 mx-auto"></div>
                    <div class="w-1/2"></div>
                </div>

            </div>
        </div>
    </section> --}}

    {{-- SECTION: KEGIATAN PERUSAHAAN (OLD) --}}
    {{-- <section id="kegiatan-perusahaan-old" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-red-700 text-center mb-12">Kegiatan Perusahaan</h2>

            <div class="grid md:grid-cols-3 gap-10">

                <div class="rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition">
                    <img src="../assets/images/training sosro.webp"
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Pelatihan Karyawan</h3>
                        <p class="text-gray-600 text-sm">Program pelatihan rutin untuk meningkatkan kompetensi tim.</p>
                    </div>
                </div>

                <div class="rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition">
                    <img src="../assets/images/outbound_sosro.png"
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Outbound & Team Building</h3>
                        <p class="text-gray-600 text-sm">Kegiatan untuk memperkuat kebersamaan dan komunikasi antar karyawan.</p>
                    </div>
                </div>

                <div class="rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition">
                    <img src="../assets/images/kegiatan sosial sosro.png"
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">CSR & Kegiatan Sosial</h3>
                        <p class="text-gray-600 text-sm">Berbagai aktivitas sosial untuk berkontribusi kepada masyarakat.</p>
                    </div>
                </div>

            </div>
        </div>
    </section> --}}

    {{-- SECTION KEGIATAN PERUSAHAAN (NEW) --}}
    <section id="kegiatan-perusahaan" class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- Judul Section --}}
            <h2 class="text-3xl md:text-4xl font-bold text-[#B11116] uppercase mb-10 text-center lg:text-left drop-shadow-sm">
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
                        ['img' => 'training sosro.webp', 'title' => 'Project <br> Strategis'],
                        ['img' => 'training sosro.webp', 'title' => 'Project <br> Strategis'],
                        ['img' => 'training sosro.webp', 'title' => 'Project <br> Strategis'],
                        ['img' => 'training sosro.webp', 'title' => 'Project <br> Strategis'],
                        ['img' => 'training sosro.webp', 'title' => 'Project <br> Strategis'],
                        ['img' => 'training sosro.webp', 'title' => 'Project <br> Strategis'],
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
        </div>
    </section>

</x-app-layout>
