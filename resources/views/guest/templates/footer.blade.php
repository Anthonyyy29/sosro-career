<footer class="bg-[#B11116] text-white pt-16 pb-8 px-6 md:px-16">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-12 gap-10">

            {{-- Kolom 1: Nama & Alamat (Lebar) --}}
            <div class="lg:col-span-4">
                <h3 class="text-2xl font-bold mb-4 uppercase tracking-tighter">PT SINAR SOSRO GUNUNG SLAMAT</h3>
                <p class="text-[15px] leading-relaxed">
                    <span class="font-bold">Kantor Pusat:</span> Graha Rekso Lantai 8 - 10. Jalan Bulevard Artha
                    Gading No.Kav.A1 RT/RW 18/08 Kelapa Gading, Jakarta Utara Daerah Khusus Ibukota Jakarta 14240
                </p>
                <p class="text-[15px] mt-4">
                    <span class="font-bold">Telepon:</span> (021) 4585 6268
                </p>
            </div>

            {{-- Kolom 2: Kontak & Jam Operasional --}}
            <div class="lg:col-span-3">
                <div class="mb-6">
                    <p class="text-[15px]">
                        <span class="font-bold">Email:</span>
                        <a href="mailto:recruitment.hrd@sosro.com" class="hover:underline">recruitment.hrd@sosro.com</a>
                        /
                        <a href="mailto:recruitment.ho@sosro.com" class="hover:underline">recruitment.ho@sosro.com</a>
                    </p>
                </div>
                <div>
                    <p class="text-[15px] leading-tight">
                        <span class="font-bold">Jam Operasional:</span><br>
                        Senin - Jumat: 08:00 17:00<br>
                        Sabtu - Minggu: Tutup
                    </p>
                </div>
            </div>

            {{-- Kolom 3: Media Sosial --}}
            <div class="lg:col-span-2">
                <h4 class="text-xl font-bold mb-4">Media Sosial</h4>
                <div class="flex space-x-4">
                    <a href="https://www.instagram.com/sosrocareer/" class="hover:opacity-80 transition text-2xl">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/pt-sinar-sosro-gunung-slamat/"
                        class="hover:opacity-80 transition text-2xl">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="https://www.tiktok.com/@lifeatsosro" class="hover:opacity-80 transition text-2xl">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>

            {{-- Kolom 4: Karier & Info (Digabung agar ringkas seperti gambar) --}}
            <div class="lg:col-span-3 grid grid-cols-2 gap-4">
                <div>
                    <h4 class="text-xl font-bold mb-4">Karier</h4>
                    <ul class="space-y-2 text-[15px]">
                        <li><a href="{{ route('guest.job') }}" class="hover:underline">Lowongan</a></li>
                        <li><a href="#" class="hover:underline">Budaya Kerja</a></li>
                        <li><a href="#" class="hover:underline">Pelatihan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-4">Info</h4>
                    <ul class="space-y-2 text-[15px]">
                        <li><a href="#" class="hover:underline">Tentang Kami</a></li>
                        <li><a href="#" class="hover:underline">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:underline">FAQ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright Section --}}
    <div class="pt-8 mt-4">
        <div class="bg-white h-0.5"></div>
        <div class=" px-3 text-center text-[16px] py-4 -mx-6 md:-mx-16 -mb-8 text-sm">
            <p>© Copyright {{ date('Y') }}. <span class="font-semibold">PT Sinar Sosro Gunung Slamat.</span> All
                Rights Reserved</p>
        </div>
    </div>
</footer>
