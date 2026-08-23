@extends('admin.layout')
@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Undangan Psikotes Massal</h2>
            <p class="text-sm text-gray-500">Atur jadwal psikotes untuk beberapa kandidat sekaligus.</p>
        </div>
        <a href="{{ route('admin.applicants') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-xl"></i>
        </a>
    </div>
    
    <form action="{{ route('admin.applicants.bulkProcess') }}" method="POST" id="bulkPsikotesForm">
    {{-- Tahap tujuan ikut dikirim, supaya controller tidak perlu menebak --}}
    <input type="hidden" name="status" value="{{ $status }}">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200" id="psikotesTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kandidat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Tanggal Tes
                            <button type="button" onclick="fillAll('date')" class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full hover:bg-blue-200 transition shadow-md">Apply All</button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Link Psikotes
                            <button type="button" onclick="fillAll('link')" class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full hover:bg-blue-200 transition shadow-md">Apply All</button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Token Psikotes (Manual)</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Tes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($applications as $index => $app)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-400">{{ $loop->iteration }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $app->applicant->user->name }}</div>
                            <div class="text-[11px] text-gray-400">{{ $app->lowongan->judul_lowongan }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <input type="date" 
                                   name="applicants[{{ $app->id }}][psikotes_date]" 
                                   class="input-date border border-gray-200 rounded-lg px-3 py-2 text-sm w-full focus:ring-2 focus:ring-blue-500 outline-none" 
                                   required 
                                   data-row="{{ $index }}">
                        </td>
                        <td class="px-6 py-4">
                            <input type="text" 
                                   name="applicants[{{ $app->id }}][psikotes_link]" 
                                   class="input-link border border-gray-200 rounded-lg px-3 py-2 text-sm w-full focus:ring-2 focus:ring-blue-500 outline-none" 
                                   placeholder="https://.." 
                                   required 
                                   data-row="{{ $index }}">
                        </td>
                        <td class="px-6 py-4">
                            <input type="text" 
                                   name="applicants[{{ $app->id }}][psikotes_token]" 
                                   class="border-2 border-blue-100 bg-blue-50/30 rounded-lg px-3 py-2 text-sm w-full focus:border-blue-500 focus:bg-white outline-none font-mono" 
                                   placeholder="Paste Token..." 
                                   required>
                        </td>
                        <td class="px-6 py-4">
                            {{-- Pilihan yang sama dengan modal satuan, supaya Tes Kepribadian
                                 juga bisa dikirim lewat jalur massal --}}
                            <select name="applicants[{{ $app->id }}][psikotes_type]"
                                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm w-full focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="psikotes">Psikotes (Standar)</option>
                                <option value="tes_kepribadian">Tes Kepribadian (Level Atas)</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex items-center justify-between bg-blue-50 p-4 rounded-lg border border-blue-100">
            <div class="flex items-center gap-3 text-blue-700">
                <i class="fas fa-info-circle"></i>
                <span class="text-sm font-medium">#Pastikan semua token sudah sesuai sebelum menekan tombol kirim.</span>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.applicants') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-800">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                    Kirim {{ $applications->count() }} Undangan Massal
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function fillAll(type) {
        // Cari inputan pertama berdasarkan class
        const firstInput = document.querySelector(`.input-${type}`);
        
        if (!firstInput || !firstInput.value) {
            alert(`Harap isi kolom ${type === 'date' ? 'Tanggal' : 'Link'} pada baris pertama terlebih dahulu!`);
            return;
        }

        const valueToCopy = firstInput.value;
        const allInputs = document.querySelectorAll(`.input-${type}`);

        // Iterasi dan masukkan value ke semua row
        allInputs.forEach((input, index) => {
            // Kita lewati index 0 karena itu sumbernya (optional, tapi lebih bersih)
            input.value = valueToCopy;
            
            // Tambahkan efek visual sedikit agar HRD tahu row sudah terisi
            input.classList.add('bg-green-50', 'border-green-200');
            setTimeout(() => {
                input.classList.remove('bg-green-50', 'border-green-200');
            }, 1000);
        });
    }

    // Shortcut: Jika Admin menekan tombol "Enter" di Link Vendor, jangan submit form tapi pindah ke token
    document.querySelectorAll('.input-link').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('tr').querySelector('input[name*="[token]"]').focus();
            }
        });
    });
</script>
@endsection
