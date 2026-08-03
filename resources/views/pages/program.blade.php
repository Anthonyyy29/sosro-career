<x-app-layout>

    {{-- HERO SECTION PROGRAM --}}
    <section class="relative w-full h-screen overflow-hidden z-0 bg-black">
        <img src="{{ asset('assets/images/program-hero.webp') }}" alt="Intip Keseruan Insan Sinar Sosro Gunung Slamat" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-red-900 via-red/80 to-transparent"></div>
        <div class="absolute inset-0 flex items-center justify-center px-6 md:px-20 max-w-7xl mx-auto">
            <div class="w-full text-center md:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl text-white font-bold uppercase leading-[1.1] md:max-w-[700px] drop-shadow-lg">
                    Peluang Menanti, Mari Berkembang Bersama
                </h1>
                <p class="text-lg md:text-3xl text-white mt-6 md:mt-8 leading-relaxed max-w-2xl">
                    Ikuti kegiatan dan program kami di PT Sinar Sosro Gunung Slamat untuk berkembang, memperluas jaringan, dan meraih kesuksesan bersama.
                </p>
            </div>
        </div>
    </section>

    <section x-data="{ activeTab: 1 }" class="py-16 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- HEADER --}}
            
            {{-- GRID CARDS --}}
            <div class="flex flex-col md:flex-row gap-4 h-[500px] mb-20 items-stretch">
                <div class="mb-12">
                    <h2 class="text-5xl font-bold text-[#B11116] drop-shadow-md uppercase">
                        NIAT<br>BAIK<br>HASIL<br>BAIK.
                    </h2>
                </div>
                
                {{-- CARD 01 - Management Trainee --}}
                <div @click="activeTab = 1" 
                    :class="activeTab === 1 ? 'md:flex-[3] flex-[1]' : 'md:flex-1 flex-[0.5]'"
                    class="relative overflow-hidden rounded-[2rem] transition-all duration-700 ease-in-out cursor-pointer group">

                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#B11116] opacity-90"></div>
                    <img src="{{ asset('assets/images/training sosro.webp') }}" 
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700"
                        :class="activeTab === 1 ? 'opacity-100 scale-100' : 'opacity-0 scale-110'">

                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div class="flex justify-between items-end">
                            <span class="[writing-mode:vertical-lr] rotate-180 font-bold text-white bg-[#B11116] px-3 py-6 rounded-full text-sm uppercase tracking-widest"
                                x-show="activeTab !== 1">Management Trainee</span>
                            <div x-show="activeTab === 1" class="bg-[#B11116] text-white px-6 py-2 rounded-full font-bold mb-4">Management Trainee</div>
                            <span class="text-7xl font-bold text-white/50" :class="activeTab === 1 ? 'text-white' : ''">01</span>
                        </div>
                    </div>
                </div>

                {{-- CARD 02 - Guru Magang --}}
                <div @click="activeTab = 2" 
                    :class="activeTab === 2 ? 'md:flex-[3] flex-[1]' : 'md:flex-1 flex-[0.5]'"
                    class="relative overflow-hidden rounded-[2rem] transition-all duration-700 ease-in-out cursor-pointer group">
                    
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#B11116] opacity-90"></div>
                    <img src="{{ asset('assets/images/training sosro.webp') }}" 
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700"
                        :class="activeTab === 2 ? 'opacity-100 scale-100' : 'opacity-0 scale-110'">

                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div class="flex justify-between items-end">
                            <span class="[writing-mode:vertical-lr] rotate-180 font-bold text-white bg-[#B11116] px-3 py-6 rounded-full text-sm uppercase tracking-widest"
                                x-show="activeTab !== 2">Guru Magang</span>
                            <div x-show="activeTab === 2" class="bg-[#B11116] text-white px-6 py-2 rounded-full font-bold mb-4">Guru Magang</div>
                            <span class="text-7xl font-bold text-white/50" :class="activeTab === 2 ? 'text-white' : ''">02</span>
                        </div>
                    </div>
                </div>

                {{-- CARD 03 - Motoris --}}
                <div @click="activeTab = 3" 
                    :class="activeTab === 3 ? 'md:flex-[3] flex-[1]' : 'md:flex-1 flex-[0.5]'"
                    class="relative overflow-hidden rounded-[2rem] transition-all duration-700 ease-in-out cursor-pointer group">

                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#B11116] opacity-90"></div>
                    <img src="{{ asset('assets/images/kegiatan sosial sosro.webp') }}" 
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700"
                        :class="activeTab === 3 ? 'opacity-100 scale-100' : 'opacity-0 scale-110'">

                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div class="flex justify-between items-end">
                            <span class="[writing-mode:vertical-rl] rotate-180 font-bold text-white bg-[#B11116] px-3 py-6 rounded-full text-[10px] uppercase leading-tight text-center"
                                x-show="activeTab !== 3">Program Motoris &<br>Sales TO Magang</span>
                            <div x-show="activeTab === 3" class="bg-[#B11116] text-white px-6 py-2 rounded-full font-bold mb-4">MHD Motoris</div>
                            <span class="text-7xl font-bold text-white/50" :class="activeTab === 3 ? 'text-white' : ''">03</span>
                        </div>
                    </div>
                </div>

                {{-- CARD 04 - Program Magang --}}
                <div @click="activeTab = 4" 
                    :class="activeTab === 4 ? 'md:flex-[3] flex-[1]' : 'md:flex-1 flex-[0.5]'"
                    class="relative overflow-hidden rounded-[2rem] transition-all duration-700 ease-in-out cursor-pointer group">

                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#B11116] opacity-90"></div>
                    <img src="{{ asset('assets/images/outbound_sosro.png') }}" 
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700"
                        :class="activeTab === 4 ? 'opacity-100 scale-100' : 'opacity-0 scale-110'">

                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div class="flex justify-between items-end">
                            <span class="[writing-mode:vertical-lr] rotate-180 font-bold text-white bg-[#B11116] px-3 py-6 rounded-full text-sm uppercase tracking-widest"
                                x-show="activeTab !== 4">Program Magang</span>
                            <div x-show="activeTab === 4" class="bg-[#B11116] text-white px-6 py-2 rounded-full font-bold mb-4">Program Magang</div>
                            <span class="text-7xl font-bold text-white/50" :class="activeTab === 4 ? 'text-white' : ''">04</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION CONTENT (DINAMIS) --}}
            
            {{-- CONTENT FOR TAB 1 - MANAGEMENT TRAINEE --}}
            <div x-show="activeTab === 1" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="text-center max-w-4xl mx-auto">
                <h3 class="text-4xl font-bold text-[#B11116] mb-6 uppercase drop-shadow-sm">MANAGEMENT TRAINEE</h3>
                <p class="text-[#B11116] text-xl font-medium mb-12">
                    Program <span class="font-bold">Management Trainee PT Sinar Sosro Gunung Slamat</span> merupakan jalur percepatan karier bagi fresh graduate dari berbagai jurusan melalui pelatihan terstruktur dan penugasan lintas unit kerja, guna menyiapkan talenta muda menjadi pemimpin masa depan perusahaan.
                </p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                </div>

                {{-- TESTIMONI SECTION --}}
                <div class="mt-24">
                    <h3 class="text-3xl font-bold text-[#B11116] mb-12 uppercase text-center drop-shadow-sm">
                        APA KATA MANAGEMENT TRAINEE KAMI?
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        {{-- CARD TESTIMONI 1 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 2 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 3 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 4 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENT FOR TAB 2 - GURU MAGANG --}}
            <div x-show="activeTab === 2" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="text-center max-w-4xl mx-auto">
                <h3 class="text-4xl font-bold text-[#B11116] mb-6 uppercase drop-shadow-sm">GURU MAGANG</h3>
                <p class="text-[#B11116] text-xl font-medium mb-12">
                    Program <span class="font-bold">Guru Magang PT Sinar Sosro Gunung Slamat</span> mendukung peningkatan kompetensi guru produktif SMK melalui pengalaman industri dan praktik kerja langsung, sekaligus membuka peluang PKL bagi siswa SMK yang selaras dengan kebutuhan dunia kerja.
                </p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                </div>

                {{-- TESTIMONI SECTION --}}
                <div class="mt-24">
                    <h3 class="text-3xl font-bold text-[#B11116] mb-12 uppercase drop-shadow-sm text-center">
                        APA KATA GURU MAGANG KAMI?
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        {{-- CARD TESTIMONI 1 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 2 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 3 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 4 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENT FOR TAB 3 - SALES --}}
            <div x-show="activeTab === 3" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="text-center max-w-4xl mx-auto">
                <h3 class="text-4xl font-bold text-[#B11116] mb-6 uppercase drop-shadow-sm">PROGRAM MOTORIS DAN SALES TO MAGANG</h3>
                <p class="text-[#B11116] text-xl font-medium mb-12">
                    <span class="font-bold">Program Motoris dan Sales TO Magang</span> membuka kesempatan bagi lulusan SMA/SMK untuk berkarier sebagai Sales Motoris ataupun Sales TO Magang di <b>PT Sinar Sosro Gunung Slamat</b>. Program ini dilengkapi dengan pelatihan, pendampingan lapangan, sertifikat kompetensi, uang saku, insentif, serta peluang menjadi karyawan tetap atau mitra usaha berdasarkan kinerja.
                </p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                </div>

                {{-- TESTIMONI SECTION --}}
                <div class="mt-24">
                    <h3 class="text-3xl font-bold text-[#B11116] mb-12 uppercase drop-shadow-sm text-center">
                        APA KATA MHD MOTORIS KAMI?
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        {{-- CARD TESTIMONI 1 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 2 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 3 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 4 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- CONTENT FOR TAB 4 - PROGRAM MAGANG --}}
            <div x-show="activeTab === 4" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="text-center max-w-4xl mx-auto">
                <h3 class="text-4xl font-bold text-[#B11116] mb-6 uppercase drop-shadow-sm">PROGRAM MAGANG</h3>
                <p class="text-[#B11116] text-xl font-medium mb-12">
                    Program <span class="font-bold">Magang/<i>Internship</i> PT Sinar Sosro Gunung Slamat</span> ditujukan bagi mahasiswa aktif untuk mengembangkan keterampilan praktis melalui keterlibatan langsung dalam aktivitas kerja, sekaligus memperluas pemahaman tentang dunia industri dan budaya perusahaan.
                </p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                    <img src="{{ asset('assets/images/training sosro.webp') }}" class="rounded-3xl h-64 w-full object-cover shadow-lg transition duration-300 ease-in-out hover:scale-105 hover:-rotate-2 hover:shadow-xl">
                </div>

                {{-- TESTIMONI SECTION --}}
                <div class="mt-24">
                    <h3 class="text-3xl font-bold text-[#B11116] mb-12 uppercase drop-shadow-sm text-center">
                        APA KATA KARYAWAN MAGANG KAMI?
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        {{-- CARD TESTIMONI 1 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 2 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 3 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                        {{-- CARD TESTIMONI 4 --}}
                        <div class="group relative pt-12">
                            
                            {{-- CARD MERAH (Setengah TINGGI dari Card Putih) --}}
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-[#B11116] rounded-[2rem] 
                                        transition-all duration-500 ease-in-out transform 
                                        {{-- Awal: Tersembunyi di belakang bagian atas card putih --}}
                                        translate-y-4 opacity-0 
                                        {{-- Hover: Meluncur ke atas --}}
                                        group-hover:-translate-y-2 group-hover:opacity-100 
                                        z-0">
                                
                                {{-- Nama di bagian paling atas card merah --}}
                                <div class="pt-3 text-center">
                                    <span class="text-white font-bold text-sm uppercase tracking-widest">
                                        AHMAD DHANI
                                    </span>
                                </div>
                            </div>

                            {{-- CARD PUTIH (Card Utama di Depan) --}}
                            <div class="relative z-10 bg-white border-2 border-[#B11116] rounded-[2rem] p-4 flex flex-col items-center 
                                        shadow-md transition-all duration-300">
                                
                                {{-- FOTO PROFIL --}}
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-6 border-4 border-gray-100 shadow-inner 
                                            group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('assets/images/default profile.webp') }}" 
                                        alt="Foto Guru Magang" 
                                        class="w-full h-full object-cover">
                                </div>

                                {{-- DEPARTEMEN --}}
                                <h4 class="text-2xl font-bold text-[#B11116] mb-4 uppercase">
                                    MARKETING
                                </h4>

                                {{-- QUOTE --}}
                                <p class="text-gray-600 text-sm leading-relaxed text-center italic">
                                    "1Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos porro aliquam eligendi, quam non pariatur esse. Architecto possimus vel error."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Placeholder untuk konten tab lain --}}
            {{-- <div x-show="activeTab !== 1 && 2" class="text-center py-20 text-gray-400 italic">
                Pilih program di atas untuk melihat detail.
            </div> --}}

        </div>
    </section>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- SECTION: MANAGEMENT TRAINEE --}}
    {{-- <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-red-700 text-center mb-12">Management Trainee</h2>

            <div class="grid md:grid-cols-2 gap-10 items-center">
                
                <img src="https://images.unsplash.com/photo-1557426272-fc759fdf7a8d"
                     class="rounded-xl shadow-lg h-80 object-cover w-full">

                <div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Program Pemimpin Masa Depan</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Management Trainee (MT) adalah program percepatan karier yang dirancang
                        untuk mencetak pemimpin muda yang siap memikul tanggung jawab strategis
                        di berbagai unit bisnis Sosro.
                    </p>
                    <ul class="text-gray-600 space-y-2">
                        <li>• Pelatihan intensif dan terstruktur</li>
                        <li>• Rotasi divisi</li>
                        <li>• Mentorship langsung dari senior leader</li>
                        <li>• Penempatan posisi strategis setelah lulus</li>
                    </ul>
                </div>

            </div>
        </div>
    </section> --}}

    {{-- SECTION: GURU MAGANG --}}
    {{-- <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-red-700 text-center mb-12">Guru Magang</h2>

            <div class="grid md:grid-cols-2 gap-10 items-center">

                <div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Program Pembelajaran Profesional</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Guru Magang merupakan program khusus untuk tenaga pendidik atau mahasiswa kependidikan 
                        yang ingin merasakan pengalaman bekerja di lingkungan perusahaan modern.
                    </p>
                    <ul class="text-gray-600 space-y-2">
                        <li>• Pelatihan komunikasi dan leadership</li>
                        <li>• Pendampingan oleh mentor berpengalaman</li>
                        <li>• Penerapan model pembelajaran di dunia industri</li>
                    </ul>
                </div>

                <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0"
                     class="rounded-xl shadow-lg h-80 object-cover w-full">

            </div>
        </div>
    </section> --}}

    {{-- SECTION: MHD MOTORIS --}}
    {{-- <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-red-700 text-center mb-12">MHD Motoris</h2>

            <div class="grid md:grid-cols-2 gap-10 items-center">

                <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952"
                     class="rounded-xl shadow-lg h-80 object-cover w-full">

                <div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Program Pengembangan Field Operation</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        MHD Motoris merupakan program untuk talenta muda yang ingin mengembangkan karier
                        di bidang distribusi dan lapangan. Peserta akan dibekali kemampuan operasional
                        dan pemahaman produk secara mendalam.
                    </p>
                    <ul class="text-gray-600 space-y-2">
                        <li>• Pelatihan pengetahuan produk</li>
                        <li>• Manajemen rute & distribusi</li>
                        <li>• Pendampingan motoris senior</li>
                    </ul>
                </div>

            </div>
        </div>
    </section> --}}

    {{-- SECTION: INTERNSHIP PROGRAM --}}
    {{-- <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-red-700 text-center mb-12">Internship Program</h2>

            <div class="grid md:grid-cols-2 gap-10 items-center">

                <div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Belajar Langsung di Dunia Industri</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Program magang (internship) terbuka untuk mahasiswa yang ingin mendapatkan pengalaman
                        bekerja nyata di berbagai divisi Sosro, dari marketing, produksi, hingga HR.
                    </p>
                    <ul class="text-gray-600 space-y-2">
                        <li>• Pengalaman kerja langsung</li>
                        <li>• Bimbingan mentor tiap divisi</li>
                        <li>• Kesempatan karier setelah lulus</li>
                    </ul>
                </div>

                <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0"
                     class="rounded-xl shadow-lg h-80 object-cover w-full">

            </div>
        </div>
    </section> --}}

</x-app-layout>
