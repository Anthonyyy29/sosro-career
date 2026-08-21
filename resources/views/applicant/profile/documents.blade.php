<x-guest-layout>
    @if ($errors->any())
        <div x-data="{ open: true }"
            x-show="open"
            x-transition
            class="relative bg-red-500 text-white p-5 rounded-2xl mb-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold mb-1">Ada kesalahan input:</p>
                    <ul class="text-sm opacity-90">
                        @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="open = false"
                        class="absolute top-4 right-4 hover:bg-white/20 rounded-full p-1 transition cursor-pointer">
                    &times;
                </button>
            </div>
        </div>
    @endif

    <div class="min-h-screen bg-[#FDFDFD] py-12 px-4 flex flex-col items-center justify-start font-figtree">
        <div class="max-w-4xl w-full">

            @if($applicant->hasAcceptedApplication())
                <div class="mb-8 p-5 bg-green-50 border border-green-100 rounded-2xl">
                    <p class="font-bold text-green-800">Selamat, lamaran Anda diterima!</p>
                    <p class="text-sm text-green-700 mt-1">Lengkapi dokumen di bawah ini untuk melanjutkan proses rekrutmen.</p>
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-[0_30px_80px_-20px_rgba(0,0,0,0.08)] border border-gray-50 overflow-hidden">
                <div class="p-6 md:p-12">
                    <form method="POST" action="{{ route('applicant.documents.update') }}" enctype="multipart/form-data"
                        x-data="{
                            fileSizes: {},
                            debugSkip: false,
                            validateFile(e, limitMb) {
                                const file = e.target.files[0];
                                const name = e.target.name;

                                if (file) {
                                    // Khusus CV harus PDF
                                    if (name === 'doc_cv') {
                                        const fileExtension = file.name.split('.').pop().toLowerCase();
                                        if (fileExtension !== 'pdf') {
                                            alert('Peringatan: Dokumen CV harus berformat PDF! File .' + fileExtension + ' tidak diizinkan.');
                                            e.target.value = '';
                                            delete this.fileSizes[name];
                                            return;
                                        }
                                    }

                                    const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                                    if (sizeMb > limitMb) {
                                        alert('File terlalu besar (' + sizeMb + 'MB). Maksimal hanya ' + limitMb + 'MB. Silakan kompres dulu ya!');
                                        e.target.value = '';
                                        delete this.fileSizes[name];
                                    } else {
                                        this.fileSizes[name] = sizeMb + ' MB';
                                    }
                                }
                            }
                        }">
                        @csrf

                        <div class="mb-10">
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Upload <span class="text-red-600">Dokumen</span></h2>
                            <p class="text-gray-400 text-sm mt-1">Format file: gambar (JPG/PNG) atau PDF. Dokumen yang sudah pernah diunggah tidak perlu diunggah ulang.</p>
                        </div>

                        @php $docsByType = $applicant->documents->keyBy('type'); @endphp

                        <div class="space-y-10">
                            @foreach(\App\Models\Applicant::DOCUMENT_GROUPS as $groupName => $fields)
                                <div>
                                    {{-- flex-shrink-0 wajib: tanpa itu garis pemisah (flex-1) mendesak
                                         judulnya sampai membungkus jadi 2 baris walau ruang masih luas --}}
                                    <div class="flex items-center gap-4 mb-5">
                                        <h3 class="flex-shrink-0 text-[11px] font-black text-gray-900 uppercase tracking-widest whitespace-nowrap">{{ $groupName }}</h3>
                                        <div class="flex-1 h-px bg-gray-200"></div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                        @foreach($fields as $type => $def)
                                            @php $existingDoc = $docsByType->get($type); @endphp
                                            <div>
                                                <div class="flex justify-between items-start gap-2 mb-1.5">
                                                    <p class="text-[11px] font-bold text-gray-600 leading-snug">
                                                        {{ $def['label'] }}
                                                        @if($def['required'])
                                                            <span class="text-red-500">*</span>
                                                        @endif
                                                        <span class="block text-[9px] font-medium text-gray-400 uppercase tracking-tight mt-0.5">
                                                            @if(! $def['required'])
                                                                {{ $def['note'] ?? 'Opsional' }} ·
                                                            @endif
                                                            Maks {{ $def['limit'] }}MB
                                                        </span>
                                                    </p>

                                                    <template x-if="fileSizes['doc_{{ $type }}']">
                                                        <span class="text-[9px] px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-bold whitespace-nowrap" x-text="'Baru: ' + fileSizes['doc_{{ $type }}']"></span>
                                                    </template>
                                                </div>

                                                <input type="file"
                                                    name="doc_{{ $type }}"
                                                    accept="{{ $type === 'cv' ? '.pdf' : 'image/*, .pdf' }}"
                                                    @change="validateFile($event, {{ $def['limit'] }})"
                                                    :required="{{ $def['required'] && ! $existingDoc ? 'true' : 'false' }} && !debugSkip"
                                                    class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold {{ $def['required'] ? 'file:bg-red-50 file:text-red-700' : 'file:bg-gray-100 file:text-gray-600' }} border border-dashed border-gray-200 p-2 rounded-lg">

                                                @if($existingDoc)
                                                    <div class="mt-2 flex items-center gap-1 text-[10px] text-green-600 font-bold italic bg-green-50 p-1.5 rounded-md border border-green-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                        Sudah ada file
                                                        <a href="{{ asset('storage/' . $existingDoc->file_path) }}" target="_blank" class="underline hover:text-green-800 ml-auto whitespace-nowrap">(Lihat)</a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <p class="mt-8 text-[11px] text-gray-400"><span class="text-red-500 font-bold">*</span> Wajib diunggah</p>

                        {{-- SEMENTARA / DEBUG: cuma tampil kalau APP_DEBUG=true, jadi tidak mungkin
                             kebawa ke produksi. Hapus blok ini bareng $debugSkip di DocumentController
                             kalau sudah tidak dibutuhkan buat testing. --}}
                        @if(config('app.debug'))
                            <label class="mt-6 flex items-start gap-3 p-4 bg-amber-50 border-2 border-dashed border-amber-300 rounded-2xl cursor-pointer">
                                <input type="checkbox" name="debug_skip_validation" value="1" x-model="debugSkip"
                                       class="mt-0.5 rounded border-amber-400 text-amber-600 focus:ring-amber-500">
                                <span class="text-xs">
                                    <span class="block font-black text-amber-800 uppercase tracking-wider">Mode Debug — Lewati Validasi</span>
                                    <span class="block text-amber-700 mt-0.5">
                                        Tandai dokumen sebagai lengkap tanpa benar-benar mengunggah file, supaya alur setelah ini bisa dites sampai selesai.
                                        Hanya muncul saat <code class="font-mono">APP_DEBUG=true</code>.
                                    </span>
                                </span>
                            </label>
                        @endif

                        <div class="mt-6 flex flex-col md:flex-row gap-4">
                            <a href="{{ route('applicant.dashboard') }}" class="h-auto md:h-14 py-4 flex-1 flex items-center justify-center bg-gray-100 rounded-2xl text-gray-600 font-bold hover:bg-gray-200 transition-all">Kembali</a>
                            <button type="submit" class="h-auto md:h-14 py-4 flex-[2] bg-green-600 rounded-2xl text-white font-bold shadow-lg shadow-green-200 hover:bg-green-700 transition-all">Simpan Dokumen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
