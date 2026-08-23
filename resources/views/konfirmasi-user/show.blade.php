<x-guest-layout>
    @php
        $sudah = $konfirmasi->sudahDipilih();
        $terpilih = $konfirmasi->selectedApplication;
    @endphp

    <div class="min-h-screen bg-[#FDFDFD] py-12 px-4 flex flex-col items-center justify-start font-figtree">
        <div class="max-w-3xl w-full">

            <div class="mb-8">
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">PT Sinar Sosro Gunung Slamat</p>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight mt-1">Konfirmasi Kandidat</h1>
                <p class="text-gray-500 text-sm mt-2">
                    Posisi <strong>{{ $konfirmasi->lowongan->judul_lowongan ?? '-' }}</strong>
                </p>
            </div>

            @if ($pesanGagal ?? null)
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-sm text-red-700">
                    {{ $pesanGagal }}
                </div>
            @endif

            @if ($sudah)
                {{-- Sudah ada keputusan. Tautan boleh dibuka lagi, tapi tidak bisa diubah dari sini. --}}
                <div class="mb-8 p-6 bg-green-50 border border-green-100 rounded-2xl">
                    <p class="font-bold text-green-800">
                        {{ ($baruDipilih ?? false) ? 'Terima kasih, pilihan Anda sudah kami terima.' : 'Kandidat untuk posisi ini sudah dipilih.' }}
                    </p>
                    <p class="text-sm text-green-700 mt-2">
                        Kandidat terpilih: <strong>{{ $terpilih->applicant->user->name ?? '-' }}</strong>
                        @if ($konfirmasi->confirmed_at)
                            <span class="block text-[12px] mt-1 opacity-80">
                                Ditentukan {{ $konfirmasi->confirmed_at->translatedFormat('d F Y, H:i') }}
                                oleh {{ $konfirmasi->dipilih_oleh === 'admin' ? 'tim rekrutmen' : 'Anda' }}.
                            </span>
                        @endif
                    </p>
                    <p class="text-sm text-green-700 mt-3">
                        Tim rekrutmen akan menindaklanjuti prosesnya. Tidak ada tindakan lain yang diperlukan.
                    </p>
                </div>
            @elseif ($kedaluwarsa ?? false)
                <div class="mb-8 p-6 bg-amber-50 border border-amber-100 rounded-2xl">
                    <p class="font-bold text-amber-800">Masa berlaku tautan ini sudah habis.</p>
                    <p class="text-sm text-amber-700 mt-2">
                        Silakan hubungi tim rekrutmen untuk meminta tautan baru. Keputusan Anda belum tercatat.
                    </p>
                </div>
            @else
                <div class="mb-8 p-5 bg-blue-50 border border-blue-100 rounded-2xl">
                    <p class="text-sm text-blue-800">
                        Berikut <strong>{{ $konfirmasi->items->count() }} kandidat</strong> beserta catatan hasil interview
                        dari tim rekrutmen. Mohon pilih <strong>satu</strong> kandidat.
                    </p>
                    <p class="text-[12px] text-blue-600 mt-2">
                        Pilihan hanya bisa dikirim sekali, jadi mohon dipastikan sebelum menekan tombol.
                    </p>
                </div>
            @endif

            <div class="space-y-4">
                @foreach ($konfirmasi->items as $item)
                    @php $iniTerpilih = $sudah && $terpilih && $terpilih->id === $item->application_id; @endphp

                    <div class="bg-white rounded-2xl border {{ $iniTerpilih ? 'border-green-300 ring-2 ring-green-100' : 'border-gray-100' }} shadow-sm p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-black text-gray-900">{{ $item->application->applicant->user->name ?? '-' }}</h3>
                                    @if ($iniTerpilih)
                                        <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-wide">Terpilih</span>
                                    @endif
                                </div>

                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mt-4">Catatan Hasil Interview</p>
                                <p class="text-sm text-gray-600 mt-1 whitespace-pre-line leading-relaxed">{{ $item->catatan_interview }}</p>
                            </div>

                            @if (! $sudah && ! ($kedaluwarsa ?? false))
                                <form method="POST"
                                      action="{{ URL::temporarySignedRoute('konfirmasi-user.select', $konfirmasi->expires_at, ['konfirmasi' => $konfirmasi->id]) }}"
                                      onsubmit="return confirm('Pilih {{ addslashes($item->application->applicant->user->name ?? 'kandidat ini') }}? Pilihan tidak bisa diubah setelah dikirim.')">
                                    @csrf
                                    <input type="hidden" name="application_id" value="{{ $item->application_id }}">
                                    <button type="submit"
                                            class="flex-shrink-0 px-5 py-2.5 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 transition shadow-lg shadow-red-100">
                                        Pilih
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="text-center text-[11px] text-gray-400 mt-10">
                Halaman ini dibuka lewat tautan pribadi dari email. Mohon tidak diteruskan ke pihak lain.
            </p>
        </div>
    </div>
</x-guest-layout>
