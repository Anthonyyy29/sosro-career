@extends('admin.layout')
@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Kirim Kandidat ke User</h2>
            <p class="text-sm text-gray-500">User akan menerima satu tautan untuk memilih <strong>salah satu</strong> kandidat di bawah ini.</p>
        </div>
        <a href="{{ route('admin.applicants') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-xl"></i>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            <p class="font-bold text-red-700 text-sm mb-1">Tidak bisa diproses:</p>
            <ul class="text-sm text-red-600 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $lowonganUnik = $applications->pluck('lowongan.judul_lowongan')->unique();
    @endphp

    @if ($lowonganUnik->count() > 1)
        {{-- Peringatan lebih awal: server juga menolak, tapi lebih baik ketahuan sebelum mengetik catatan panjang --}}
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
            <p class="font-bold text-amber-800 text-sm">Kandidat berasal dari {{ $lowonganUnik->count() }} lowongan berbeda.</p>
            <p class="text-sm text-amber-700 mt-1">
                User diminta memilih satu orang untuk satu posisi, jadi kandidatnya harus dari lowongan yang sama.
                Silakan kembali dan pilih ulang.
            </p>
        </div>
    @endif

    <form action="{{ route('admin.user-confirmations.store') }}" method="POST">
        @csrf

        <div class="mb-6 bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email User</label>
            <input type="email" name="email_user" value="{{ old('email_user') }}" required
                   placeholder="nama@perusahaan.co.id"
                   class="w-full md:w-1/2 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <p class="text-xs text-gray-400 mt-2">
                Satu tautan dikirim ke alamat ini. Penerimanya tidak perlu punya akun, dan tautannya berlaku
                {{ \App\Http\Controllers\Admin\UserConfirmationController::MASA_BERLAKU_HARI }} hari.
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Posisi: <strong>{{ $lowonganUnik->implode(', ') }}</strong>
            </p>
        </div>

        <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kandidat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan Hasil Interview</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($applications as $app)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 align-top">
                            <div class="text-sm font-semibold text-gray-400">{{ $loop->iteration }}</div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <div class="text-sm font-semibold text-gray-900">{{ $app->applicant->user->name }}</div>
                            <div class="text-[11px] text-gray-400">{{ $app->lowongan->judul_lowongan }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <textarea name="catatan[{{ $app->id }}]" rows="3" required
                                      placeholder="Ringkasan hasil interview yang akan dibaca user sebagai bahan pertimbangan..."
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">{{ old('catatan.'.$app->id) }}</textarea>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex items-center justify-between bg-blue-50 border border-blue-100 rounded-xl px-5 py-4">
            <p class="text-sm text-blue-700">
                Catatan ini akan <strong>terlihat oleh user</strong>. Status kandidat menjadi "Konfirmasi User".
            </p>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.applicants') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
                <button type="submit" @disabled($lowonganUnik->count() > 1)
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition disabled:opacity-40 disabled:cursor-not-allowed">
                    Kirim ke User ({{ $applications->count() }} kandidat)
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
