<x-guest-layout>
    <div class="py-8 md:py-12 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
        
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-800 tracking-tight">
                    Riwayat <span class="text-red-600">Lamaran Saya</span>
                </h1>
                <p class="text-gray-500 mt-1 font-medium">Pantau status dan perkembangan lamaran pekerjaan Anda.</p>
            </div>
            <div class="bg-red-50 px-4 py-2 rounded-2xl border border-red-100">
                <span class="text-red-700 font-bold text-sm">Total Lamaran: {{ $applications->count() }}</span>
            </div>
        </div>

        @if($applications->isEmpty())
            {{-- Empty State --}}
            <div class="bg-white border border-gray-100 rounded-[2rem] p-12 text-center shadow-sm">
                <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-9">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Belum Ada Lamaran</h3>
                <p class="text-gray-500 mb-6">Sepertinya Anda belum melamar pekerjaan apapun saat ini.</p>
                <a href="{{ route('lowongan') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                    Cari Lowongan Sekarang
                </a>
            </div>
        @else
            {{-- List Section --}}
            <div class="grid gap-6">
                @foreach($applications as $app)
                    @php
                        // 'submitted' -- nilai default enum lama sebelum status jadi varchar bebas, gak pernah ditulis kode manapun sekarang, dianggap alias 'pending'.
                        $statusKey = $app->status === 'submitted' ? 'pending' : $app->status;
                        $statusData = [
                            'color' => App\Models\RecruitmentStage::colors()[$statusKey] ?? 'bg-gray-50 text-gray-700 border-gray-100',
                            'label' => App\Models\RecruitmentStage::applicantLabels()[$statusKey]
                                ?? App\Models\RecruitmentStage::labels()[$statusKey]
                                ?? str_replace('_', ' ', $app->status),
                            'step' => $statusKey === 'pending' ? 1 : (in_array($statusKey, ['accepted', 'rejected']) ? 3 : 2),
                        ];
                    @endphp

                    <div class="bg-white border border-gray-100 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6 md:p-8">
                            <div class="flex flex-col lg:flex-row justify-between gap-6">
                                
                                {{-- Left Side: Job Info --}}
                                <div class="flex-1">
                                    <div class="flex items-start gap-4">
                                        <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center border border-gray-100 flex-shrink-0">
                                            <span class="text-xl font-black text-red-600">{{ substr($app->lowongan->judul_lowongan, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-black text-gray-800 leading-tight">{{ $app->lowongan->judul_lowongan }}</h2>
                                            <div class="flex flex-wrap items-center gap-y-2 gap-x-4 mt-2">
                                                <span class="text-sm font-medium text-gray-400 flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    {{ $app->lowongan->cabang->nama ?? 'Kantor Pusat' }}
                                                </span>
                                                <span class="text-sm font-medium text-gray-400 flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Terlamar Pada: {{ $app->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Pesan status rejected --}}
                                    @if($app->status === 'rejected')
                                        <div class="mt-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                Ditolak (Rejected)
                                            </span>
                                            <p class="text-xs text-gray-500 mt-2 italic">
                                                * Terima kasih telah melamar. Mohon cek email Anda untuk detail informasi.
                                            </p>
                                        </div>
                                    @endif

                                    {{-- Pesan status accepted (Hired) --}}
                                    @if($app->status === 'accepted')
                                        <div class="mt-4 p-4 bg-green-50 rounded-2xl border border-green-100 border-l-4 border-l-green-400">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white uppercase tracking-wider">
                                                    Lolos Seleksi (Hired)
                                                </span>
                                            </div>
                                            
                                            <div class="space-y-2">
                                                <p class="text-sm text-green-800 font-semibold">Selamat! Anda terpilih menjadi bagian dari tim kami.</p>
                                                <p class="text-xs text-gray-600 leading-relaxed">
                                                    Informasi mengenai penempatan kerja, jadwal onboarding, dan dokumen yang perlu disiapkan telah kami kirimkan ke email Anda. Silakan melakukan pengecekan secara berkala.
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Instruksi Dinamis sesuai Status --}}
                                    @if($app->status === 'psikotes')
                                        <div class="mt-4 p-4 bg-blue-50 rounded-2xl border border-blue-100">
                                            <p class="text-sm text-blue-700">
                                                <strong>Instruksi Psikotes:</strong> Silakan cek email Anda secara berkala untuk mendapatkan link tes dan token akses.
                                            </p>
                                        </div>
                                    @elseif($app->status === 'interview')
                                        <div class="mt-4 p-4 bg-cyan-50 rounded-2xl border border-cyan-100">
                                            <p class="text-sm text-cyan-700">
                                                <strong>Tahap Interview:</strong> Tim HRD kami akan menghubungi Anda melalui WhatsApp atau Email untuk jadwal wawancara.
                                            </p>
                                        </div>
                                    @elseif($app->status === 'offering')
                                        <div class="mt-4 p-4 bg-pink-50 rounded-2xl border border-pink-100 italic font-medium">
                                            <p class="text-sm text-pink-700">
                                                Selamat! Cek email Anda untuk meninjau dokumen Offering Letter yang kami kirimkan.
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Right Side: Stepper/Timeline --}}
                                <div class="lg:w-72 flex-shrink-0">
                                    <div class="relative">
                                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-100"></div>
                                        
                                        {{-- Step 1 --}}
                                        <div class="relative flex items-center mb-4 ml-1">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center z-10 {{ $statusData['step'] >= 1 ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-400' }}">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <span class="ml-4 text-xs font-bold {{ $statusData['step'] >= 1 ? 'text-gray-800' : 'text-gray-400' }}">Lamaran Terkirim</span>
                                        </div>

                                        {{-- Step 2 --}}
                                        <div class="relative flex items-center mb-4 ml-1">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center z-10 {{ $statusData['step'] >= 2 ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-400' }}">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <span class="ml-4 text-xs font-bold {{ $statusData['step'] >= 2 ? 'text-gray-800' : 'text-gray-400' }}">
                                                {{ $statusData['step'] == 2 ? $statusData['label'] : 'Proses Seleksi' }}
                                            </span>
                                        </div>

                                        {{-- Step 3 (Final) --}}
                                        <div class="relative flex items-center ml-1">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center z-10 
                                                {{ $app->status == 'accepted' ? 'bg-green-500 text-white' : ($app->status == 'rejected' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-400') }}">
                                                @if($app->status == 'accepted')
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                @elseif($app->status == 'rejected')
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                @else
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                @endif
                                            </div>
                                            <span class="ml-4 text-xs font-bold {{ $statusData['step'] >= 3 ? 'text-gray-800' : 'text-gray-400' }}">Hasil Akhir</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Footer Card --}}
                        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Status Saat Ini:</span>
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase border {{ $statusData['color'] }}">
                                    {{ $statusData['label'] }}
                                </span>
                            </div>
                            {{-- Cukup arahkan ke route lowongan saja --}}
                            <a href="{{ route('lowongan') }}" class="text-xs font-bold text-red-600 hover:underline">
                                Cari Lowongan Lain &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Back Button --}}
        <div class="mt-8 flex justify-center">
            <a href="{{ route('dashboard') }}" class="group inline-flex items-center px-6 py-2 bg-white border border-gray-200 text-gray-500 font-bold rounded-full hover:text-red-600 hover:border-red-100 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</x-guest-layout>