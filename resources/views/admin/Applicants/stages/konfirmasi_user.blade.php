{{-- Isian tambahan untuk tahap Konfirmasi User (jalur satu pelamar).
     Isinya sama dengan form massal, cuma untuk satu orang: alamat user yang
     akan memilih, dan catatan interview yang akan dia baca. --}}
<div x-show="nextStatus === 'konfirmasi user'" x-transition class="p-4 bg-amber-50 rounded-xl border border-amber-100 space-y-3">
    <p class="text-[11px] font-bold text-amber-600 uppercase">Kirim ke User</p>

    <div>
        <label class="text-xs text-gray-500">Email User</label>
        <input type="email" name="email_user" placeholder="nama@perusahaan.co.id"
               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1">
    </div>

    <div>
        <label class="text-xs text-gray-500">Catatan Hasil Interview</label>
        <textarea name="catatan_interview" rows="3"
                  placeholder="Ringkasan yang akan dibaca user sebagai bahan pertimbangan..."
                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1"></textarea>
    </div>

    <p class="text-[11px] text-amber-700 italic">
        * User menerima satu tautan untuk memilih. Kalau ingin menyodorkan beberapa kandidat sekaligus,
        centang mereka di daftar lalu pakai Update Massal.
    </p>
</div>
