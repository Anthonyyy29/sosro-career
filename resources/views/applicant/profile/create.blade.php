<x-guest-layout>
    @if ($errors->any())
        <div x-data="{ open: true }" 
            x-show="open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
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
                    <svg xmlns="http:                                                                                             
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif
    <div x-data="{ 
        step: 1,
        // Fitur Autofill Domisili
        isSameAddress: false,
        alamat: '',
        domisili: '',
        
        // Data Dinamis
        keluargaInti: [{ nama:'', hubungan:'', tempat_lahir:'', tgl_lahir:'', pendidikan:'', pekerjaan:'', hp:'' }],
        keluargaKandung: [{ nama:'', hubungan:'', tempat_lahir:'', tgl_lahir:'', pendidikan:'', pekerjaan:'', hp:'' }],
        pendidikanFormal: [{ jenjang:'', sekolah:'', jurusan:'', nilai:'', tahun_masuk:'', tahun_lulus:'', is_current_edu: 0 }],
        pendidikanInformal: [{ kursus: '', penyelenggara: '', tahun: '' }],
        pengalamanKerja: [{  perusahaan: '', jabatan: '', divisi: '', tanggal_masuk: '', tanggal_keluar: '', gaji: '', fasilitas: '', kontak_referensi: '', alasan: '', masih_bekerja: 0 }],

        // Logic Helper
        syncDomisili() {
            if(this.isSameAddress) { this.domisili = this.alamat; }
        }
    }" class="min-h-screen bg-[#FDFDFD] py-12 px-4 flex flex-col items-center justify-center font-figtree">
        
        <div class="max-w-4xl w-full">
            
            <div class="mb-10 relative px-4">
                <div class="flex justify-between items-center relative z-10">
                    <template x-for="(label, index) in ['Personal', 'Keluarga', 'Pendidikan', 'Pengalaman', 'Dokumen']">
                        <div class="flex flex-col items-center">
                            <div :class="step >= index + 1 ? 'bg-red-600 border-red-100 text-white' : 'bg-gray-200 text-gray-400'" 
                                 class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 border-4 mb-2">
                                <span class="font-bold text-sm" x-text="index + 1"></span>
                            </div>
                            <span class="hidden md:block text-[10px] font-bold uppercase tracking-tighter" :class="step >= index + 1 ? 'text-red-600' : 'text-gray-400'" x-text="label"></span>
                        </div>
                    </template>
                </div>
                <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-100 -z-0"></div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-[0_30px_80px_-20px_rgba(0,0,0,0.08)] border border-gray-50 overflow-hidden">
                <div class="p-6 md:p-12">
                    <form method="POST" action="{{ route('applicant.profile.store') }}" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div x-show="step === 1" x-transition>
                            <div class="mb-8">
                                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Data <span class="text-red-600">Personal</span></h2>
                                <p class="text-gray-400 text-sm">Lengkapi identitas sesuai KTP.</p>
                                <span class="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter">
                                    * Wajib diisi
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">NIK (Sesuai KTP) <span class="text-red-500">*</span></label>
                                    <input name="nik" :required="step === 1" type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="16" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                                    <input type="text" value="{{ Auth::user()->name }}" disabled class="w-full px-5 py-3 bg-gray-100 text-gray-500 rounded-xl border-2 border-transparent font-semibold">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Email</label>
                                    <input type="text" value="{{ Auth::user()->email }}" disabled class="w-full px-5 py-3 bg-gray-100 text-gray-500 rounded-xl border-2 border-transparent font-semibold">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">No. Telepon / WA <span class="text-red-500">*</span></label>
                                    <input name="phone" value="{{ old('phone') }}" :required="step === 1" type="text" pattern="[0-9+\s\-]{10,20}" inputmode="numeric"
                                        class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 {{ $errors->has('phone') ? 'border-red-500' : 'border-transparent' }} focus:border-red-500 outline-none">
                                    @error('phone')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <div class="flex gap-6 py-2">
                                        <label class="inline-flex items-center"><input type="radio" name="jk" value="L" class="text-red-600 focus:ring-red-500"><span class="ml-2 text-sm text-gray-600">Laki-laki</span></label>
                                        <label class="inline-flex items-center"><input type="radio" name="jk" value="P" class="text-red-600 focus:ring-red-500"><span class="ml-2 text-sm text-gray-600">Perempuan</span></label>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Agama <span class="text-red-500">*</span></label>
                                    <select name="agama" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none">
                                        <option value="">Pilih Agama</option>
                                        <option>Islam</option><option>Kristen</option><option>Katolik</option><option>Hindu</option><option>Budha</option><option>Lainnya</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Tempat Lahir <span class="text-red-500">*</span></label>
                                        <input name="tempat_lahir" type="text" :required="step === 1" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Tanggal Lahir <span class="text-red-500">*</span></label>
                                        <input name="tanggal_lahir" type="date" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none">
                                    </div>
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Alamat Lengkap (Sesuai KTP) <span class="text-red-500">*</span></label>
                                    <textarea x-model="alamat" name="alamat" :required="step === 1" rows="2" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none"></textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="inline-flex items-center mb-2">
                                        <input type="checkbox" x-model="isSameAddress" @change="syncDomisili()" class="rounded text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-xs font-bold text-gray-500 uppercase">Domisili saat ini sama dengan KTP</span>
                                    </label>
                                    <textarea x-model="domisili" :disabled="isSameAddress" name="domisili" rows="2" placeholder="Alamat Domisili" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none disabled:opacity-50"></textarea>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Username Instagram</label>
                                        <div class="relative flex items-center">
                                            <span class="absolute left-4 text-gray-400 text-sm">@</span>
                                            <input name="instagram" type="text" placeholder="username" class="w-full pl-8 pr-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">URL LinkedIn</label>
                                        <input name="linkedin" type="text" placeholder="linkedin.com/in/username" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                    </div>
                                </div>
                                <div class="md:col-span-2 space-y-3">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Kepemilikan SIM (Pilih semua yang Anda miliki)</label>
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                        <template x-for="sim in ['A', 'B1', 'B2', 'C', 'D']">
                                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl border-2 border-transparent hover:border-red-100 cursor-pointer transition-all group">
                                                <input type="checkbox" 
                                                    name="jenis_sim[]" 
                                                    :value="sim" 
                                                    class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                <span class="ml-3 text-sm font-bold text-gray-600 group-hover:text-red-600" x-text="'SIM ' + sim"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Tinggi Badan (cm) <span class="text-red-500">*</span></label>
                                        <input name="tinggi_badan" type="number" placeholder="Contoh: 170" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Berat Badan (kg) <span class="text-red-500">*</span></label>
                                        <input name="berat_badan" type="number" placeholder="Contoh: 65" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                    </div>
                                </div>
                                <div class="md:col-span-2 space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Status Pernikahan <span class="text-red-500">*</span></label>
                                    <select name="status_nikah" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none">
                                        <option>Belum Menikah</option><option>Menikah</option><option>Duda</option><option>Janda</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 space-y-4" x-data="{ isExEmployee: '{{ old('ex_employee', $applicant->profile->ex_employee ?? 'Tidak') }}' }">
                                    {{-- Radio Button Section "md:col-span-2" --}}
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Pernah Bekerja Disini? <span class="text-red-500">*</span></label>
                                        <div class="flex gap-6 py-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="ex_employee" value="Ya" x-model="isExEmployee" class="text-red-600">
                                                <span class="ml-2 text-sm text-gray-600">Ya</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="ex_employee" value="Tidak" x-model="isExEmployee" class="text-red-600">
                                                <span class="ml-2 text-sm text-gray-600">Tidak</span>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Form Tambahan (Hanya muncul jika pilih 'Ya') --}}
                                    <div x-show="isExEmployee === 'Ya'" 
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-red-50/50 rounded-xl border border-red-100">
                                        
                                        <div>
                                            <label class="text-[10px] font-bold text-gray-400 uppercase">Nama Perusahaan / Unit Sebelumnya</label>
                                            <input type="text" name="ex_company_name" 
                                                value="{{ old('ex_company_name', $applicant->profile->ex_company_name ?? '') }}"
                                                placeholder="Nama Perusahaan atau Cabang X"
                                                class="mt-1 block w-full border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
                                        </div>

                                        <div>
                                            <label class="text-[10px] font-bold text-gray-400 uppercase">Jabatan Terakhir</label>
                                            <input type="text" name="ex_last_position" 
                                                value="{{ old('ex_last_position', $applicant->profile->ex_last_position ?? '') }}"
                                                placeholder="Contoh: Staff Produksi"
                                                class="mt-1 block w-full border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-2 space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Riwayat Penyakit (Jika ada) <span class="text-red-500">*</span></label>
                                    <input name="penyakit" type="text" placeholder="Sebutkan jika ada, jika tidak isi '-'" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                </div>
                                <div class="md:col-span-2 grid grid-cols-2 gap-6">
                                    <div class="flex-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Apakah Anda Perokok? <span class="text-red-500">*</span></label>
                                        <select name="perokok" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                            {{-- <option value="" disabled selected>Pilih Jawaban</option> --}}
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Apakah Anda Bertato? <span class="text-red-500">*</span></label>
                                        <select name="bertato" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                            {{-- <option value="" disabled selected>Pilih Jawaban</option> --}}
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Input lain-lain --}}
                                <div class="md:col-span-2 grid grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Gaji yang Diharapkan <span class="text-red-500">*</span></label>
                                        <input type="text" name="expected_salary" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all" placeholder="Contoh: Rp 6.500.000">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Fasilitas yang Diharapkan <span class="text-red-500">*</span></label>
                                        <input name="expected_facilities" rows="2" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all" placeholder="Sebutkan"></input>
                                    </div>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-2 gap-6">
                                    <div class="flex-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Bersedia Dinas Luar Kota? <span class="text-red-500">*</span></label>
                                        <select name="ready_dinas" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Bersedia Ditempatkan di Luar Kota? <span class="text-red-500">*</span></label>
                                        <select name="ready_placed_out" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="md:col-span-2 space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Referensi di Perusahaan Ini</label>
                                    <input type="text" name="company_reference" placeholder="Nama Referensi / Jabatan (Jika ada)" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                </div>

                                {{-- SECTION: URUTAN MINAT --}}
                                <div class="md:col-span-2 space-y-1">
                                    <div class="mb-4">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Urutkan Minat Dalam Bekerja (Prioritas Atas ke Bawah)</label>
                                        {{-- <p class="text-[10px] text-red-500 mt-1">* Geser (drag) urutan di bawah ini dari yang paling Anda minati (atas) ke yang kurang diminati (bawah)</p> --}}
                                        <p class="text-[10px] text-red-500 mt-1">* Geser kotak untuk merubah urutan minat Anda</p>
                                    </div>

                                    <div id="minat-list" class="space-y-3">
                                        @foreach($jobFields as $index => $field)
                                        <div class="flex items-center bg-gray-50 p-1 rounded-xl border-2 border-transparent hover:border-red-500 transition-all cursor-move group">
                                            <div class="flex items-center justify-center w-8 h-8 bg-white rounded-lg shadow-sm mr-4">
                                                <span class="text-xs font-black text-red-600 rank-number">{{ $index + 1 }}</span>
                                            </div>
                                            <span class="text-sm font-bold text-gray-700">{{ $field->nama }}</span>
                                            <input type="hidden" name="minat_ordered[]" value="{{ $field->id }}">

                                            {{-- Grip Icon --}}
                                            <div class="ml-auto text-gray-300 group-hover:text-red-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M7 7h2v2H7V7zm0 4h2v2H7v-2zm4-4h2v2h-2V7zm0 4h2v2h-2v-2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="step === 2" x-transition>
                            <div class="mb-8">
                                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Data <span class="text-red-600">Keluarga</span></h2>
                                <p class="text-gray-400 text-sm">Informasi keluarga inti dan kandung.</p>
                                <span class="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter">
                                    * Bagi anggota keluarga yang sudah meninggal, harap tetap dicantumkan dan beri tanda plus <strong>+</strong> di belakang nama.
                                </span>
                            </div>

                            <div class="mb-10">
                                <label class="text-[11px] font-black text-red-600 uppercase tracking-widest block mb-4">Keluarga Inti (Suami/Istri/Anak)</label>
                                <template x-for="(item, index) in keluargaInti" :key="index">
                                    <div class="relative p-5 mb-4 bg-gray-50 rounded-3xl border border-gray-100 hover:border-red-200 transition-all shadow-sm">
                                        <button type="button" @click="keluargaInti.splice(index, 1)" class="absolute -top-2 -right-2 bg-white text-gray-400 hover:text-red-600 shadow-md rounded-full p-1.5 border border-gray-100 transition-transform hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>

                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                                            <div class="md:col-span-5">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Nama Lengkap</label>
                                                <input type="text" :name="'k_inti['+index+'][nama]'" placeholder="Nama sesuai identitas" class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Hubungan</label>
                                                <input type="text" :name="'k_inti['+index+'][hubungan]'" placeholder="Suami/Istri/Anak ke-1" class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-4">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Pendidikan Terakhir</label>
                                                <select :name="'k_inti['+index+'][pendidikan]'" class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm appearance-none">
                                                    <option value="">Pilih Pendidikan</option>
                                                    <option>SD</option><option>SMP</option><option>SMA/SMK</option><option>D3</option><option>S1</option><option>S2</option><option>Tidak Sekolah</option>
                                                </select>
                                            </div>

                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tempat Lahir</label>
                                                <input type="text" :name="'k_inti['+index+'][tempat_lahir]'" placeholder="Kota Lahir" class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tanggal Lahir</label>
                                                <input type="date" :name="'k_inti['+index+'][tgl_lahir]'" class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Pekerjaan</label>
                                                <input type="text" :name="'k_inti['+index+'][pekerjaan]'" placeholder="Cth: Karyawan Swasta" class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">No HP / WhatsApp</label>
                                                <input type="text" :name="'k_inti['+index+'][hp]'" placeholder="0812..." class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="keluargaInti.push({nama:'', hubungan:'', tempat_lahir:'', tgl_lahir:'', pendidikan:'', pekerjaan:'', hp:''})" class="mt-2 text-[10px] font-black text-red-600 uppercase tracking-widest bg-red-50 px-5 py-2.5 rounded-xl hover:bg-red-100 transition-all">+ Tambah Anggota Keluarga</button>
                            </div>

                            <div class="mb-10">
                                <label class="text-[11px] font-black text-red-600 uppercase tracking-widest block mb-4">Keluarga Kandung (Orang Tua / Saudara Kandung)</label>
                                
                                <template x-for="(item, index) in keluargaKandung" :key="index">
                                    <div class="relative p-5 mb-4 bg-gray-50/80 rounded-3xl border border-gray-100 hover:border-red-200 transition-all shadow-sm">
                                        
                                        <button type="button" @click="keluargaKandung.splice(index, 1)" 
                                                class="absolute -top-2 -right-2 bg-white text-gray-400 hover:text-red-600 shadow-md rounded-full p-1.5 border border-gray-100 transition-transform hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>

                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                                            <div class="md:col-span-5">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Nama Lengkap</label>
                                                <input type="text" :name="'k_kandung['+index+'][nama]'" placeholder="Nama sesuai identitas" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Hubungan</label>
                                                <input type="text" :name="'k_kandung['+index+'][hubungan]'" placeholder="Ayah/Ibu/Kakak/Adik" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-4">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Pendidikan Terakhir</label>
                                                <select :name="'k_kandung['+index+'][pendidikan]'" class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm appearance-none">
                                                    <option value="">Pilih Pendidikan</option>
                                                    <option>SD</option><option>SMP</option><option>SMA/SMK</option><option>D3</option><option>S1</option><option>S2</option><option>Tidak Sekolah</option>
                                                </select>
                                            </div>

                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tempat Lahir</label>
                                                <input type="text" :name="'k_kandung['+index+'][tempat_lahir]'" placeholder="Kota lahir" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tanggal Lahir</label>
                                                <input type="date" :name="'k_kandung['+index+'][tgl_lahir]'" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Pekerjaan</label>
                                                <input type="text" :name="'k_kandung['+index+'][pekerjaan]'" placeholder="Cth: Karyawan Swasta" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">No HP / WhatsApp</label>
                                                <input type="text" :name="'k_kandung['+index+'][hp]'" placeholder="0812..." 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <button type="button" @click="keluargaKandung.push({nama:'', hubungan:'', tempat_lahir:'', tgl_lahir:'', pendidikan:'', pekerjaan:'', hp:''})" 
                                        class="mt-2 text-[10px] font-black text-red-600 uppercase tracking-widest bg-red-50 px-5 py-2.5 rounded-xl hover:bg-red-100 transition-all border border-red-100">
                                    + Tambah Keluarga Kandung
                                </button>
                            </div>
                        </div>

                        <div x-show="step === 3" x-transition>
                            <div class="mb-8">
                                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Riwayat <span class="text-red-600">Pendidikan</span></h2>
                            </div>

                            <div class="mb-10">
                                <label class="text-[11px] font-black text-red-600 uppercase tracking-widest block mb-4">Pendidikan Formal</label>
                                
                                <template x-for="(item, index) in pendidikanFormal" :key="index">
                                    <div class="relative p-5 mb-6 bg-gray-50/80 rounded-[2rem] border-2 border-transparent hover:border-red-100 transition-all shadow-sm">
                                        
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="bg-red-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter">
                                                Pendidikan #<span x-text="index + 1"></span>
                                            </span>
                                            <button type="button" @click="pendidikanFormal.splice(index, 1)" 
                                                    class="text-gray-400 hover:text-red-600 transition-colors p-1 bg-white rounded-full shadow-sm border border-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="flex flex-col gap-4">
                                            <div class="flex flex-col md:flex-row gap-4">
                                                <div class="w-full md:w-1/4">
                                                    <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Jenjang</label>
                                                    <select :name="'pendidikan_formal['+index+'][jenjang]'" 
                                                            x-model="item.jenjang" 
                                                            class="w-full mt-1 px-4 py-3 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm appearance-none">
                                                        <option value="">Pilih Jenjang</option>
                                                        <option>SMA/SMK</option>
                                                        <option>D3</option>
                                                        <option>S1</option>
                                                        <option>S2</option>
                                                    </select>
                                                </div>

                                                <div class="flex-1">
                                                    <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Nama Sekolah / Kampus</label>
                                                    <input type="text" :name="'pendidikan_formal['+index+'][sekolah]'" 
                                                        x-model="item.sekolah" 
                                                        placeholder="Universitas Indonesia" 
                                                        class="w-full mt-1 px-4 py-3 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                                </div>

                                                <div class="flex-1">
                                                    <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Fakultas / Jurusan</label>
                                                    <input type="text" :name="'pendidikan_formal['+index+'][jurusan]'" 
                                                        x-model="item.jurusan" 
                                                        placeholder="Manajemen / IPS" 
                                                        class="w-full mt-1 px-4 py-3 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                                </div>

                                                <div class="flex-1">
                                                    <label class="text-[9px] font-black text-gray-400 uppercase ml-1">IPK / Nilai</label>
                                                    <input type="text" :name="'pendidikan_formal['+index+'][nilai]'" 
                                                        x-model="item.nilai" 
                                                        placeholder="3.50" 
                                                        class="w-full mt-1 px-4 py-3 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm text-center">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end bg-white/50 p-4 rounded-2xl border border-gray-100/50">
                                                <div>
                                                    <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tahun Masuk</label>
                                                    <input type="number" :name="'pendidikan_formal['+index+'][tahun_masuk]'" 
                                                        x-model="item.tahun_masuk" 
                                                        placeholder="2020" 
                                                        class="w-full mt-1 px-4 py-2.5 bg-white rounded-lg text-sm border-none focus:ring-2 focus:ring-red-500 outline-none text-center shadow-sm">
                                                </div>
                                                
                                                <div>
                                                    <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tahun Lulus</label>
                                                    <input type="number" 
                                                        :name="'pendidikan_formal['+index+'][tahun_lulus]'" 
                                                        x-model="item.tahun_lulus"
                                                        :disabled="item.is_current_edu == '1' || item.is_current_edu === true"
                                                        :placeholder="(item.is_current_edu == '1' || item.is_current_edu === true) ? '---' : '2024'" 
                                                        :class="(item.is_current_edu == '1' || item.is_current_edu === true) ? 'bg-gray-100 text-gray-300' : 'bg-white'"
                                                        class="w-full mt-1 px-4 py-2.5 rounded-lg text-sm border-none focus:ring-2 focus:ring-red-500 outline-none text-center shadow-sm transition-all">
                                                </div>

                                                <div class="flex items-center h-10">
                                                    <label class="inline-flex items-center cursor-pointer group">
                                                        <input type="hidden" :name="'pendidikan_formal['+index+'][is_current_edu]'" value="0">
                                                        
                                                        <input type="checkbox" 
                                                            :name="'pendidikan_formal['+index+'][is_current_edu]'" 
                                                            x-model="item.is_current_edu"
                                                            :checked="item.is_current_edu == '1' || item.is_current_edu == true"
                                                            @change="item.is_current_edu = $el.checked ? '1' : '0'; if($el.checked) item.tahun_lulus = ''"
                                                            class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                                        
                                                        <span class="ml-2 text-[10px] font-black text-gray-400 group-hover:text-red-600 transition-colors uppercase tracking-tight">
                                                            Masih menempuh pendidikan
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <button type="button" @click="pendidikanFormal.push({jenjang:'', sekolah:'', jurusan:'', nilai:'', tahun_masuk:'', tahun_lulus:'', is_current_edu: '0'})" 
                                        class="w-full py-4 border-2 border-dashed border-gray-200 rounded-[1.5rem] text-gray-400 text-xs font-black uppercase tracking-widest hover:border-red-200 hover:text-red-500 hover:bg-red-50/30 transition-all">
                                    + Tambah Riwayat Pendidikan
                                </button>
                            </div>

                            <div class="mb-10">
                                <label class="text-[11px] font-black text-red-600 uppercase tracking-widest block mb-4">Pendidikan Informal (Kursus / Training)</label>
                                <template x-for="(item, index) in pendidikanInformal" :key="index">
                                    <div class="flex flex-col md:flex-row gap-3 mb-3 items-end bg-gray-50/50 p-4 rounded-2xl relative group">
                                        <div class="flex-[2] w-full">
                                            <label class="text-[9px] font-bold text-gray-400 uppercase ml-1">Nama Kursus / Sertifikasi</label>
                                            <input type="text" :name="'pendidikan_informal['+index+'][kursus]'" placeholder="Contoh: Digital Marketing / Welder Certification" class="w-full px-4 py-3 bg-white rounded-xl text-sm border-2 border-transparent focus:border-red-500 outline-none">
                                        </div>
                                        <div class="flex-1 w-full">
                                            <label class="text-[9px] font-bold text-gray-400 uppercase ml-1">Penyelenggara</label>
                                            <input type="text" :name="'pendidikan_informal['+index+'][penyelenggara]'" placeholder="Nama Lembaga" class="w-full px-4 py-3 bg-white rounded-xl text-sm border-2 border-transparent focus:border-red-500 outline-none">
                                        </div>
                                        <div class="w-full md:w-32">
                                            <label class="text-[9px] font-bold text-gray-400 uppercase ml-1">Tahun</label>
                                            <input type="number" :name="'pendidikan_informal['+index+'][tahun]'" placeholder="2023" class="w-full px-4 py-3 bg-white rounded-xl text-sm border-2 border-transparent focus:border-red-500 outline-none">
                                        </div>
                                        <button type="button" @click="pendidikanInformal.splice(index, 1)" class="p-3 text-gray-400 hover:text-red-600 mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </template>
                                <button type="button" @click="pendidikanInformal.push({kursus:'', penyelenggara:'', tahun:''})" class="mt-2 text-xs font-black text-red-600 uppercase tracking-wider bg-red-50 px-4 py-2 rounded-lg hover:bg-red-100 transition-all">
                                    + Tambah Kursus/Training
                                </button>
                            </div>
                        </div>

                        <div x-show="step === 4" x-transition>
                            <div class="mb-8">
                                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Pengalaman <span class="text-red-600">Kerja</span></h2>
                                <p class="text-gray-400 text-sm">Lampirkan maksimal 3 perusahaan terakhir.</p>
                            </div>

                            <template x-for="(item, index) in pengalamanKerja" :key="index">
                                <div class="bg-gray-50 p-6 rounded-[2rem] mb-6 relative border-2 border-transparent hover:border-red-100 transition-all shadow-sm">
                                    
                                    {{-- Tombol Hapus --}}
                                    <button type="button" 
                                            @click="pengalamanKerja.splice(index, 1)" 
                                            class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center bg-white text-gray-400 hover:text-red-600 rounded-full shadow-sm hover:shadow transition-all group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        {{-- Nama Perusahaan --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Nama Perusahaan & Bidang Industri</label>
                                            <input type="text" :name="'pengalaman_kerja['+index+'][perusahaan]'" 
                                                x-model="item.perusahaan" 
                                                placeholder="PT. Nama Perusahaan (FMCG)" 
                                                class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm">
                                        </div>

                                        {{-- Jabatan --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Jabatan</label>
                                            <input type="text" :name="'pengalaman_kerja['+index+'][jabatan]'" 
                                                x-model="item.jabatan" 
                                                placeholder="Contoh: Staff Administrasi" 
                                                class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm">
                                        </div>

                                        {{-- Divisi --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Divisi / Departemen</label>
                                            <input type="text" :name="'pengalaman_kerja['+index+'][divisi]'" 
                                                x-model="item.divisi" 
                                                placeholder="Contoh: HRD / Produksi" 
                                                class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm">
                                        </div>

                                        {{-- Periode Kerja --}}
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="space-y-1">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tanggal Masuk</label>
                                                <input type="date" :name="'pengalaman_kerja['+index+'][tanggal_masuk]'" 
                                                    x-model="item.tanggal_masuk" 
                                                    class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tanggal Keluar</label>
                                                <input type="date" :name="'pengalaman_kerja['+index+'][tanggal_keluar]'" 
                                                    x-model="item.tanggal_keluar"
                                                    :disabled="item.masih_bekerja == '1' || item.masih_bekerja === true"
                                                    :class="(item.masih_bekerja == '1' || item.masih_bekerja === true) ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-white'"
                                                    class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm transition-all">
                                            </div>
                                        </div>

                                        {{-- Ceklis Masih Bekerja --}}
                                        <div class="md:col-span-2 flex items-center px-1">
                                            <label class="inline-flex items-center cursor-pointer group">
                                                <input type="hidden" :name="'pengalaman_kerja['+index+'][masih_bekerja]'" value="0">
                                                <input type="checkbox" :name="'pengalaman_kerja['+index+'][masih_bekerja]'" 
                                                    x-model="item.masih_bekerja"
                                                    :checked="item.masih_bekerja == '1' || item.masih_bekerja === true"
                                                    @change="item.masih_bekerja = $el.checked ? '1' : '0'; if($el.checked) { item.tanggal_keluar = ''; item.alasan = ''; }"
                                                    class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                                <span class="ml-2 text-[10px] font-black text-gray-500 uppercase tracking-tight group-hover:text-red-600 transition-colors">Saya masih bekerja di sini</span>
                                            </label>
                                        </div>

                                        {{-- Gaji --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Gaji Terakhir (Take Home Pay)</label>
                                            <input type="text" :name="'pengalaman_kerja['+index+'][gaji]'" 
                                                x-model="item.gaji" 
                                                placeholder="Rp 0.000.000" 
                                                class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm">
                                        </div>

                                        {{-- Fasilitas --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Fasilitas / Tunjangan</label>
                                            <input type="text" :name="'pengalaman_kerja['+index+'][fasilitas]'" 
                                                x-model="item.fasilitas" 
                                                placeholder="Cth: BPJS, Jemputan, dll" 
                                                class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm">
                                        </div>

                                        {{-- Referensi --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Kontak Referensi (Atasan/HRD)</label>
                                            <input type="text" :name="'pengalaman_kerja['+index+'][kontak_referensi]'" 
                                                x-model="item.kontak_referensi" 
                                                placeholder="Nama & No HP" 
                                                class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm">
                                        </div>

                                        {{-- Alasan Keluar --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Alasan Keluar / Resign</label>
                                            <input type="text" :name="'pengalaman_kerja['+index+'][alasan]'" 
                                                x-model="item.alasan"
                                                :disabled="item.masih_bekerja == '1' || item.masih_bekerja === true"
                                                :class="(item.masih_bekerja == '1' || item.masih_bekerja === true) ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-white'"
                                                :placeholder="(item.masih_bekerja == '1' || item.masih_bekerja === true) ? 'Masih aktif bekerja' : 'Sebutkan alasan'" 
                                                class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm transition-all">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <button type="button" 
                                    @click="if(pengalamanKerja.length < 3) pengalamanKerja.push({perusahaan: '', jabatan: '', divisi: '', tanggal_masuk: '', tanggal_keluar: '', masih_bekerja: '0', gaji: '', fasilitas: '', kontak_referensi: '', alasan: ''})" 
                                    x-show="pengalamanKerja.length < 3" 
                                    class="w-full py-4 border-2 border-dashed border-gray-200 rounded-2xl text-gray-400 font-bold hover:border-red-300 hover:text-red-500 transition-all uppercase text-[11px] tracking-widest">
                                + Tambah Pengalaman Kerja
                            </button>
                        </div>

                        <div x-show="step === 5" x-transition 
                            x-data="{ 
                                fileSizes: {}, 
                                validateFile(e, limitMb) {
                                    const file = e.target.files[0];
                                    const name = e.target.name;
                                    
                                    if (file) {
                                        // 1. PROTEKSI EKSTENSI (Khusus CV harus PDF)
                                        if (name === 'doc_cv') {
                                            const fileExtension = file.name.split('.').pop().toLowerCase();
                                            if (fileExtension !== 'pdf') {
                                                alert('Peringatan: Dokumen CV harus berformat PDF! File .' + fileExtension + ' tidak diizinkan.');
                                                e.target.value = ''; // Reset inputan file
                                                delete this.fileSizes[name];
                                                return; // Berhenti di sini, jangan lanjut cek ukuran
                                            }
                                        }

                                        // 2. PROTEKSI UKURAN (Sudah ada sebelumnya)
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
                            
                            <div class="mb-8">
                                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Upload <span class="text-red-600">Dokumen</span></h2>
                                <span class="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter">
                                    * File: Image atau PDF
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Sisi Kiri: Wajib --}}
                                <div class="space-y-4">
                                    <label class="text-[11px] font-black text-red-600 uppercase tracking-widest">Wajib Diunggah</label>
                                    <div class="space-y-4">
                                        @foreach([
                                            'doc_foto' => ['label' => 'PAS FOTO TERBARU UKURAN 3x4 (Maks 2MB)', 'limit' => 2, 'accept' => 'image/*, .pdf'],
                                            'doc_cv' => ['label' => 'CV (Maks 5MB)', 'limit' => 5, 'accept' => 'image/*, .pdf'],
                                            'doc_ktp' => ['label' => 'KTP (Maks 2MB)', 'limit' => 2, 'accept' => 'image/*, .pdf'],
                                            'doc_ijazah' => ['label' => 'IJAZAH / TRANSKRIP NILAI (Maks 2MB)', 'limit' => 2, 'accept' => 'image/*, .pdf']
                                        ] as $name => $info)
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $info['label'] }}</p>
                                                <template x-if="fileSizes['{{ $name }}']">
                                                    <span class="text-[9px] px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-bold" x-text="fileSizes['{{ $name }}']"></span>
                                                </template>
                                            </div>
                                            <input type="file" 
                                                name="{{ $name }}" 
                                                accept="{{ $info['accept'] }}"
                                                @change="validateFile($event, {{ $info['limit'] }})" 
                                                {{ isset($applicant) ? '' : 'required' }}
                                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-700 border border-dashed border-gray-200 p-2 rounded-lg">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Sisi Kanan: Pendukung --}}
                                <div class="space-y-4">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Pendukung (Maks 2MB/file)</label>
                                    <div class="space-y-4">
                                        @foreach(['doc_sim', 'doc_npwp', 'doc_bpjs_kes', 'doc_bpjs_tk', 'doc_lain'] as $extra)
                                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-[10px] font-bold text-gray-500 uppercase">{{ str_replace('doc_', '', $extra) }}</span>
                                                <template x-if="fileSizes['{{ $extra }}']">
                                                    <span class="text-[9px] text-blue-600 font-bold" x-text="fileSizes['{{ $extra }}']"></span>
                                                </template>
                                            </div>
                                            <input type="file" name="{{ $extra }}" @change="validateFile($event, 2)" class="w-full text-xs">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 flex flex-col md:flex-row gap-4">
                            <template x-if="step > 1">
                                <button type="button" @click="step--; window.scrollTo(0,0)" class="h-auto md:h-14 py-4 flex-1 h-14 bg-gray-100 rounded-2xl text-gray-600 font-bold hover:bg-gray-200 transition-all">Kembali</button>
                            </template>

                            <template x-if="step < 5">
                                <button type="button" 
                                    @click="
                                        const currentStepEl = $el.closest('form').querySelector(`div[x-show='step === ${step}']`);
                                        const inputs = currentStepEl.querySelectorAll('[required]');
                                        let isValid = true;

                                        inputs.forEach(input => {
                                            if (!input.value || (input.type === 'checkbox' && !input.checked)) {
                                                isValid = false;
                                                input.classList.add('border-red-500', 'bg-red-50');
                                            } else {
                                                input.classList.remove('border-red-500', 'bg-red-50');
                                            }
                                        });

                                        if (isValid) {
                                            step++;
                                            window.scrollTo(0,0);
                                        } else {
                                            alert('Mohon isi semua bidang yang wajib diisi sebelum melanjutkan.');
                                        }
                                    " 
                                    class="h-auto md:h-14 py-4 flex-[2] h-14 bg-red-600 rounded-2xl text-white font-bold shadow-lg shadow-red-200 hover:bg-red-700 transition-all flex items-center justify-center gap-2">
                                    Lanjutkan Ke Tahap Berikutnya
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                            </template>

                            <template x-if="step === 5">
                                <button type="submit" onclick="this.innerHTML='Menyimpan...'; this.classList.add('opacity-50')" class="h-auto md:h-14 py-4 flex-[2] h-14 bg-green-600 rounded-2xl text-white font-bold shadow-lg shadow-green-200 hover:bg-green-700 transition-all">Kirim Seluruh Data Lamaran</button>
                            </template>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        new Sortable(document.getElementById('minat-list'), {
            animation: 100,
            ghostClass: 'bg-red-50',
            onEnd: function() {
                document.querySelectorAll('.rank-number').forEach((el, i) => el.innerText = i + 1);
            }
        });
    </script>
</x-guest-layout>