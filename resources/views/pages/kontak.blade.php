<x-app-layout>
    {{-- HERO SECTION KONTAK --}}
    <section class="relative w-full h-screen overflow-hidden z-0 bg-black">
        <img src="{{ asset('assets/images/kontak-hero.webp') }}" alt="Intip Keseruan Insan Sinar Sosro Gunung Slamat" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-red-900 via-red/80 to-transparent"></div>
        <div class="absolute inset-0 flex items-center justify-center px-6 md:px-20 max-w-7xl mx-auto">
            <div class="w-full text-center md:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl text-white font-bold uppercase leading-[1.1] md:max-w-[700px] drop-shadow-lg">
                    Cari Tahu Lebih Tentang Karier di Sinar Sosro Gunung Slamat 
                </h1>
                <p class="text-lg md:text-3xl text-white mt-6 md:mt-8 leading-relaxed max-w-2xl">
                    Kami siap membantu! <br>Hubungi kami melalui form di bawah ini.</br>
                </p>
            </div>
        </div>
    </section>
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-12 text-center">
            <h1 class="text-3xl font-bold text-[#B11116] mb-6 uppercase drop-shadow-sm">Kirim Pesan</h1>

            <form action="{{ route('contact.store') }}" method="POST" class="max-w-md mx-auto bg-white p-6 rounded-lg drop-shadow-lg mb-20">
                @csrf
                
                {{-- Alert Berhasil --}}
                {{-- @if(session('success_modal'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        {{ session('success_modal') }}
                    </div>
                @endif --}}
                @if(session('success_modal'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-start space-x-3 animate-fade-in-down">
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-green-800 font-medium">{{ session('success_modal') }}</p>
                    </div>
                @endif
                
                {{-- Honeypot - Anti Spam Bot --}}
                <div class="hidden">
                    <input type="text" name="confirm_email_address" tabindex="-1" autocomplete="off">
                </div>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4">
                    <input type="text" name="name" placeholder="Nama Lengkap" required
                        class="w-full border-gray-300 rounded-md p-2 focus:ring-red-600 focus:border-[#B11116] @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}">
                </div>

                <div class="mb-4">
                    <input type="email" name="email" placeholder="Email" required
                        class="w-full border-gray-300 rounded-md p-2 focus:ring-red-600 focus:border-[#B11116] @error('email') border-red-500 @enderror"
                        value="{{ old('email') }}">
                </div>

                <div class="mb-4">
                    <select name="city" required class="w-full text-gray-500 border-gray-300 rounded-md p-2 focus:ring-red-600 focus:border-[#B11116]">
                        <option value="" disabled selected>Wilayah Tujuan</option>
                        <option value="Kantor Pusat" class="font-semibold">Kantor Pusat</option>
                        <optgroup label="Wilayah:" class="font-semibold">
                            <option value="KPW Bali Nusra">Bali Nusra</option>
                            <option value="KPW Jakarta Banten">Jakarta Banten</option>
                            <option value="KPW Jawa Barat">Jawa Barat</option>
                            <option value="KPW Jawa Tengah">Jawa Tengah</option>
                            <option value="KPW Jawa Timur">Jawa Timur</option>
                            <option value="KPW Kalimantan Sulawesi">Kalimantan Sulawesi Maluku Papua</option>
                            <option value="KPW Sumbagsel Babel">Sumatera Selatan, Bangka Belitung</option>
                            
                            {{-- Warning !!! --}}
                            <option value="KPW Sumut NAD - Sumbar Kepri">Sumatera Utara, NAD, Sumatera Barat, Kepulauan Riau</option>
                        </optgroup>
                        <optgroup label="Pabrik:" class="font-semibold">
                            <option value="Pabrik Cakung">Pabrik Cakung</option>
                            <option value="Pabrik Cibitung">Pabrik Cibitung</option>
                            <option value="Pabrik Deli Serdang">Pabrik Deli Serdang</option>
                            <option value="Pabrik Gianyar">Pabrik Gianyar</option>
                            <option value="Pabrik Mojokerto">Pabrik Mojokerto</option>
                            <option value="Pabrik Palembang">Pabrik Palembang</option>
                            <option value="Pabrik Pandaan">Pabrik Pandaan</option>
                            <option value="Pabrik Purbalingga">Pabrik Purbalingga</option>
                            <option value="Pabrik Sentul">Pabrik Sentul</option>
                            <option value="Pabrik Slawi">Pabrik Slawi</option>
                            <option value="Pabrik Ungaran">Pabrik Ungaran</option>
                        </optgroup>
                        <optgroup label="Lainnya:" class="font-semibold">
                            <option value="Kebun">Perkebunan</option>
                            <option value="Poci Kreasi Mandiri">Poci Kreasi Mandiri</option>
                        </optgroup>
                    </select>
                </div>

                <div class="mb-4">
                    <textarea name="message" placeholder="Pesan" rows="4" required
                            class="w-full border-gray-300 rounded-md p-2 focus:ring-red-600 focus:border-[#B11116]">{{ old('message') }}</textarea>
                </div>

                {{-- Turnstile Container --}}
                <div class="mb-4 py-2 flex flex-col items-center justify-center">
                    <x-turnstile />
                    @error('cf-turnstile-response') 
                        <p class="text-red-500 text-[10px] mt-2 font-semibold uppercase tracking-wider">Verifikasi Keamanan Diperlukan</p> 
                    @enderror
                </div>

                <button type="submit"
                    class="bg-[#B11116] text-white md:text-lg px-5 py-3 rounded-full font-medium hover:bg-red-800 transition inline-flex items-center gap-2 leading-none w-full justify-center">
                    Kirim Pesan
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                </button>
            </form>

            {{-- START: Bagian FAQ (Accordion) --}}
            <h2 class="text-3xl font-bold text-[#B11116] mb-10 mt-10 uppercase drop-shadow-sm">
                Pertanyaan yang Sering Diajukan (FAQ)
            </h2>

            <div class="max-w-3xl mx-auto space-y-4 text-left" x-data="faqsData()">

                <template x-for="(faq, index) in faqs" :key="index">
                    <div class="border border-gray-200 rounded-lg shadow-sm bg-white">

                        {{-- Header --}}
                        <button @click="faq.open = !faq.open"
                            class="flex justify-between items-center w-full p-4 font-semibold text-white bg-[#B11116] transition duration-300 rounded-t-lg focus:outline-none">
                            <span x-text="faq.q"></span>

                            <svg :class="{ 'rotate-180': faq.open }"
                                class="w-5 h-5 text-white transition-transform duration-300"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Isi --}}
                        <div x-show="faq.open" class="p-4 border-t border-gray-200 text-gray-800 font-medium leading-relaxed">
                            <p x-text="faq.a"></p>
                        </div>

                    </div>
                </template>

            </div>
            {{-- END FAQ --}}

        </div>
    </section>
</x-app-layout>

<script>
    // Data FAQ yang akan di-looping oleh Alpine.js
    document.addEventListener('alpine:init', () => {
        Alpine.data('faqsData', () => ({
            faqs: [
                { q: 'Tahapan apa saja jika ingin melamar di PT Sinar Sosro Gunung Slamat?', a: 'Tahapan umumnya: Pendaftaran Akun, Melengkapi Profil, Pemilihan Lowongan, Seleksi oleh HRD/User, hingga Offering.' },
                { q: 'Apa yang harus dilakukan jika lupa password?', a: 'Gunakan fitur "Lupa Kata Sandi" di halaman login. Masukkan email terdaftar Anda, dan ikuti instruksi yang dikirimkan ke email Anda untuk membuat password baru.' },
                { q: 'Dokumen apa saja yang harus dipersiapkan sebelum membuat akun?', a: 'Siapkan CV terbaru, Ijazah Pendidikan Terakhir, Transkrip Nilai, dan KTP dalam format digital (misalnya PDF) untuk diunggah.' },
                { q: 'Mengapa saya gagal menyimpan data?', a: 'Cek koneksi internet Anda. Pastikan format file dokumen sudah benar (misalnya PDF) dan ukurannya tidak melebihi batas maksimum yang ditetapkan.' },
                { q: 'Apakah saya bisa melamar lebih dari satu posisi?', a: 'Ya, pada umumnya diperbolehkan, namun kami sangat menyarankan Anda fokus melamar pada posisi yang paling sesuai dengan kualifikasi dan minat Anda.' },
                { q: 'Setelah selesai melamar posisi yang dituju, apakah tahapan selanjutnya?', a: 'Tahap selanjutnya adalah Seleksi Administrasi (Review CV) oleh Tim Rekrutmen. Kandidat yang lolos akan dihubungi untuk tahapan tes/wawancara.' },
                { q: 'Apa saja tahapan proses seleksi yang harus diikuti?', a: 'Tahapan seleksi meliputi Seleksi Administrasi, Psikotes, Wawancara HRD, Wawancara User, dan Tes Kesehatan (MCU). Tahapan bisa bervariasi per posisi.' },
                { q: 'Berapa jangka waktu dari masing-masing tahapan proses seleksi?', a: 'Jangka waktu proses seleksi dapat memakan waktu 2 hingga 4 minggu sejak tanggal penutupan lamaran. Mohon menunggu informasi resmi dari HRD.' },
                { q: 'Bagaimana cara saya melihat status proses untuk setiap posisi yang sudah saya lamar?', a: 'Anda dapat melihat status terbaru melalui menu "Riwayat Lamaran" setelah Anda masuk ke akun pelamar.' },
                { q: 'Apakah saya bisa melakukan update terhadap profil dan CV saya?', a: 'Ya, Anda dapat memperbarui data profil dan mengunggah CV terbaru di akun Anda. Perubahan akan berlaku untuk lamaran yang akan datang.' },
                { q: 'Apakah ada pungutan biaya dalam proses seleksi?', a: 'TIDAK ADA. Seluruh proses rekrutmen PT Sinar Sosro Gunung Slamat bersifat GRATIS. Harap abaikan segala permintaan pembayaran yang mengatasnamakan perusahaan.' }
            ]
        }))
    })
</script>
