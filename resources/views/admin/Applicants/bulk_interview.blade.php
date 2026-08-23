@extends('admin.layout')
@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Undangan Interview Massal</h2>
        <p class="text-sm text-gray-500">Atur jadwal interview untuk beberapa kandidat sekaligus.</p>
    </div>
    
    <form action="{{ route('admin.applicants.bulkProcess') }}" method="POST">
    <input type="hidden" name="status" value="{{ $status }}">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kandidat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                            Tipe
                            <button type="button" onclick="fillAll('type')" class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full hover:bg-blue-200 transition shadow-md">Apply All</button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                            Waktu (Tgl & Jam)
                            <button type="button" onclick="fillAll('date')" class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full hover:bg-blue-200 transition shadow-md">Apply All</button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                            Link / Lokasi Kantor
                            <button type="button" onclick="fillAll('link')" class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full hover:bg-blue-200 transition shadow-md">Apply All</button>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($applications as $app)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-500">{{ $loop->iteration }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold">{{ $app->applicant->user->name }}</div>
                            <div class="text-[11px] text-gray-400">{{ $app->lowongan->judul_lowongan }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <select name="applicants[{{ $app->id }}][interview_type]" class="input-type border rounded-lg px-2 py-1.5 text-sm w-full">
                                <option value="initial">Interview Awal (Online)</option>
                                <option value="lanjutan">Interview Lanjutan (Online)</option>
                                <option value="offline">Interview Offline (Tatap Muka)</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <input type="datetime-local" name="applicants[{{ $app->id }}][interview_date]" class="input-date border rounded-lg px-2 py-1.5 text-sm w-full" required>
                        </td>
                        <td class="px-6 py-4">
                            <input type="text" name="applicants[{{ $app->id }}][interview_link]" class="input-link border rounded-lg px-2 py-1.5 text-sm w-full" placeholder="Link Zoom atau Alamat Kantor" required>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex items-center justify-between bg-blue-50 p-4 rounded-lg border border-blue-100">
            <div class="flex items-center gap-3 text-blue-700">
                <i class="fas fa-info-circle"></i>
                <span class="text-sm font-medium">#Pastikan semua data sudah sesuai sebelum menekan tombol kirim.</span>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.applicants') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-800">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                    Kirim {{ $applications->count() }} Undangan Interview
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function fillAll(type) {
        const firstInput = document.querySelector(`.input-${type}`);
        if (!firstInput.value) return alert('Isi baris pertama dulu!');

        document.querySelectorAll(`.input-${type}`).forEach(input => {
            input.value = firstInput.value;
        });
    }
</script>
@endsection
