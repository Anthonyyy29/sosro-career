<div x-data="{ open: true }" x-show="open" x-cloak @click.self="open = false"
    class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex justify-center items-center z-50 px-3">
    <div class="bg-white max-w-lg mx-auto max-md:p-3 md:p-6 rounded-xl shadow-xl border-l-8 border-red-700 relative">

        <button @click="open = false" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
            &times;
        </button>

        <h2 class="text-2xl font-bold text-red-700 mb-3">⚠ Waspada Penipuan!</h2>

        <p class="text-gray-700 leading-relaxed">
            <strong>HATI-HATI TERHADAP PENIPUAN LOWONGAN PEKERJAAN!</strong>
            <br><br>
        <p class="text-justify">Waspadalah terhadap pihak yang mengatasnamakan
            <strong>PT Sinar Sosro Gunung Slamat</strong>.
            Kami <strong>tidak pernah</strong> meminta pelamar membayar biaya apapun selama proses rekrutmen.
        </p>
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
