<x-guest-layout>
    @if ($errors->any())
        <div class="bg-red-500 text-white p-5 rounded-2xl mb-6">
            <p class="font-bold">Ada kesalahan input:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div x-data="{ 
        step: 1,
        
        // Fitur Autofill Domisili (Ambil data lama)
        alamat: '{{ old('alamat', $applicant->profile->alamat) }}',
        domisili: '{{ old('domisili', $applicant->profile->domisili) }}',
        isSameAddress: {{ old('alamat', $applicant->profile->alamat) === old('domisili', $applicant->profile->domisili) ? 'true' : 'false' }},
        
        keluargaInti: {{ Js::from(old('k_inti', $applicant->profile->data_keluarga['inti'] ?? [])) }},
        keluargaKandung: {{ Js::from(old('k_kandung', $applicant->profile->data_keluarga['kandung'] ?? [])) }},

        init() {
            // Jika data dari database kosong, buatkan 1 baris kosong agar form tidak hilang
            if (this.keluargaInti.length === 0) {
                this.keluargaInti.push({ nama:'', hubungan:'', tempat_lahir:'', tgl_lahir:'', pendidikan:'', pekerjaan:'', hp:'' });
            }
            if (this.keluargaKandung.length === 0) {
                this.keluargaKandung.push({ nama:'', hubungan:'', tempat_lahir:'', tgl_lahir:'', pendidikan:'', pekerjaan:'', hp:'' });
            }
        },

        pendidikanFormal: {{ Js::from(old('pendidikan_formal', $applicant->profile->pendidikan_formal ?? [[
            'jenjang' => '', 
            'sekolah' => '', // diubah dari instansi
            'jurusan' => '', 
            'nilai' => '', 
            'tahun_masuk' => '', 
            'tahun_lulus' => '', 
            'is_current_edu' => false
        ]])) }},
        
        pendidikanInformal: {{ Js::from(old('pendidikan_informal', $applicant->profile->pendidikan_informal ?? [['kursus' => '', 'penyelenggara' => '', 'tahun' => '']])) }},
        
        pengalamanKerja: {{ Js::from(old('pengalaman_kerja', $applicant->profile->pengalaman_kerja ?? [[
            'perusahaan' => '', 
            'jabatan' => '', 
            'divisi' => '', 
            'tanggal_masuk' => '',    // Sesuaikan dengan x-model di form
            'tanggal_keluar' => '',   // Sesuaikan dengan x-model di form
            'masih_bekerja' => false, 
            'gaji' => '', 
            'fasilitas' => '', 
            'kontak_referensi' => '', 
            'alasan' => ''
        ]])) }},

        // Logic Helper
        syncDomisili() {
            if(this.isSameAddress) { 
                this.domisili = this.alamat; 
            }
        }
    }" class="min-h-screen bg-[#FDFDFD] py-12 px-4 flex flex-col items-center justify-center font-sans">
        
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
                    <form method="POST" action="{{ route('applicant.profile.update') }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

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
                                    <input value="{{ old('nik', $applicant->profile->nik) }}" name="nik" :required="step === 1" type="text" inputmode="numeric" maxlength="16" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
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
                                    <input value="{{ old('phone', $applicant->profile->phone) }}" name="phone" :required="step === 1" type="tel" pattern="[0-9]{10,15}" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <div class="flex gap-6 py-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="jk" value="L" class="text-red-600 focus:ring-red-500" {{ old('jk', $applicant->profile->jk) == 'L' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-600">Laki-laki</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="jk" value="P" class="text-red-600 focus:ring-red-500" {{ old('jk', $applicant->profile->jk) == 'P' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-600">Perempuan</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Agama <span class="text-red-500">*</span></label>
                                    <select name="agama" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none">
                                        <option value="">Pilih Agama</option>
                                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Lainnya'] as $agama)
                                            <option value="{{ $agama }}" {{ old('agama', $applicant->profile->agama) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Tempat Lahir <span class="text-red-500">*</span></label>
                                        <input name="tempat_lahir" value="{{ old('tempat_lahir', $applicant->profile->tempat_lahir) }}" type="text" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Tanggal Lahir <span class="text-red-500">*</span></label>
                                        <input name="tanggal_lahir" value="{{ old('tanggal_lahir', \Carbon\Carbon::parse($applicant->profile->tanggal_lahir)->format('Y-m-d')) }}" type="date" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none">
                                    </div>
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Alamat lengkap (Sesuai KTP) <span class="text-red-500">*</span></label>
                                    <textarea x-model="alamat" name="alamat" rows="2" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none">{{ old('alamat', $applicant->profile->alamat) }}</textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="inline-flex items-center mb-2">
                                        <input type="checkbox" x-model="isSameAddress" @change="syncDomisili()" class="rounded text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-xs font-bold text-gray-500 uppercase">Domisili saat ini sama dengan KTP</span>
                                    </label>
                                    <textarea x-model="domisili" :disabled="isSameAddress" name="domisili" rows="2" placeholder="Alamat Domisili" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none disabled:opacity-50">{{ old('domisili', $applicant->profile->domisili) }}</textarea>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Username Instagram</label>
                                        <div class="relative flex items-center">
                                            <span class="absolute left-4 text-gray-400 text-sm">@</span>
                                            <input name="instagram" value="{{ old('instagram', $applicant->profile->instagram) }}" type="text" placeholder="username" class="w-full pl-8 pr-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">URL LinkedIn</label>
                                        <input name="linkedin" value="{{ old('linkedin', $applicant->profile->linkedin) }}" type="text" placeholder="linkedin.com/in/username" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                    </div>
                                </div>
                                <div class="md:col-span-2 space-y-3">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Kepemilikan SIM</label>
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                        @php $savedSim = $applicant->profile->jenis_sim ?? []; @endphp
                                        @foreach(['A', 'B1', 'B2', 'C', 'D'] as $sim)
                                            <label class="flex items-center p-4 bg-gray-50 rounded-2xl border-2 border-transparent hover:border-red-100 cursor-pointer transition-all group">
                                                <input type="checkbox" name="jenis_sim[]" value="{{ $sim }}" 
                                                    {{ in_array($sim, old('jenis_sim', $savedSim)) ? 'checked' : '' }}
                                                    class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                <span class="ml-3 text-sm font-bold text-gray-600 group-hover:text-red-600">SIM {{ $sim }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Tinggi Badan (cm) <span class="text-red-500">*</span></label>
                                        <input name="tinggi_badan" value="{{ old('tinggi_badan', $applicant->profile->tinggi_badan) }}" type="number" placeholder="Contoh: 170" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Berat Badan (kg) <span class="text-red-500">*</span></label>
                                        <input name="berat_badan" value="{{ old('berat_badan', $applicant->profile->berat_badan) }}" type="number" placeholder="Contoh: 65" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                    </div>
                                </div>
                                <div class="md:col-span-2 space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Status Pernikahan <span class="text-red-500">*</span></label>
                                    <select name="status_nikah" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none">
                                        @foreach(['Belum Menikah', 'Menikah', 'Duda', 'Janda'] as $status)
                                            <option value="{{ $status }}" {{ old('status_nikah', $applicant->profile->status_nikah) == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Tambahkan md:col-span-2 di sini supaya dia LEBAR ke bawah, tidak terjepit di kanan --}}
                                <div class="md:col-span-2 space-y-4" x-data="{ isExEmployee: '{{ old('ex_employee', $applicant->profile->ex_employee ?? 'Tidak') }}' }">
                                    
                                    {{-- Radio Button Section (Sama persis dengan Create) --}}
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Pernah Bekerja Disini? <span class="text-red-500">*</span></label>
                                        <div class="flex gap-6 py-2">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="ex_employee" value="Ya" x-model="isExEmployee" class="text-red-600">
                                                <span class="ml-2 text-sm text-gray-600">Ya</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
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
                                                {{-- PERBEDAAN: Ditambah value untuk Edit --}}
                                                value="{{ old('ex_company_name', $applicant->profile->ex_company_name ?? '') }}"
                                                placeholder="Nama Perusahaan atau Cabang X"
                                                class="mt-1 block w-full border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500 p-2">
                                        </div>

                                        <div>
                                            <label class="text-[10px] font-bold text-gray-400 uppercase">Jabatan Terakhir</label>
                                            <input type="text" name="ex_last_position" 
                                                {{-- PERBEDAAN: Ditambah value untuk Edit --}}
                                                value="{{ old('ex_last_position', $applicant->profile->ex_last_position ?? '') }}"
                                                placeholder="Contoh: Staff Produksi"
                                                class="mt-1 block w-full border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500 p-2">
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-2 space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Riwayat Penyakit (Jika ada) <span class="text-red-500">*</span></label>
                                    <input name="penyakit" value="{{ old('penyakit', $applicant->profile->penyakit) }}" type="text" placeholder="Sebutkan jika ada, jika tidak isi '-'" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                </div>
                                <div class="md:col-span-2 grid grid-cols-2 gap-6">
                                    <div class="flex-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Apakah Anda Perokok? <span class="text-red-500">*</span></label>
                                        <select name="perokok" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                            <option value="Ya" {{ ucfirst(old('perokok', $applicant->profile->perokok)) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                            <option value="Tidak" {{ ucfirst(old('perokok', $applicant->profile->perokok)) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Apakah Anda Bertato? <span class="text-red-500">*</span></label>
                                        <select name="bertato" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                            <option value="Ya" {{ ucfirst(old('bertato', $applicant->profile->bertato)) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                            <option value="Tidak" {{ ucfirst(old('bertato', $applicant->profile->bertato)) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Input Lain-lain (Edit Mode) --}}
                                <div class="md:col-span-2 grid grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Gaji yang Diharapkan <span class="text-red-500">*</span></label>
                                        <input type="text" name="expected_salary" 
                                            value="{{ old('expected_salary', $applicant->profile->expected_salary) }}" 
                                            class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all" 
                                            placeholder="Contoh: Rp 6.500.000">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Fasilitas yang Diharapkan <span class="text-red-500">*</span></label>
                                        <input name="expected_facilities" 
                                            value="{{ old('expected_facilities', $applicant->profile->expected_facilities) }}"
                                            class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all" 
                                            placeholder="Sebutkan">
                                    </div>
                                </div>

                                <div class="md:col-span-2 grid grid-cols-2 gap-6">
                                    <div class="flex-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Bersedia Dinas Luar Kota? <span class="text-red-500">*</span></label>
                                        <select name="ready_dinas" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                            <option value="Ya" {{ old('ready_dinas', $applicant->profile->ready_dinas) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                            <option value="Tidak" {{ old('ready_dinas', $applicant->profile->ready_dinas) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Bersedia Ditempatkan di Luar Kota? <span class="text-red-500">*</span></label>
                                        <select name="ready_placed_out" class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                            <option value="Ya" {{ old('ready_placed_out', $applicant->profile->ready_placed_out) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                            <option value="Tidak" {{ old('ready_placed_out', $applicant->profile->ready_placed_out) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="md:col-span-2 space-y-1">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Referensi di Perusahaan Ini</label>
                                    <input type="text" name="company_reference" 
                                        value="{{ old('company_reference', $applicant->profile->company_reference) }}"
                                        placeholder="Nama karyawan / Jabatan (Jika ada)" 
                                        class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all">
                                </div>

                                {{-- SECTION: URUTAN MINAT (Edit Mode) --}}
                                <div class="md:col-span-2 space-y-1">
                                    <div class="mb-4">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Urutkan Minat Dalam Bekerja (Sesuai Pilihan Anda Sebelumnya)</label>
                                        <p class="text-[10px] text-red-500 mt-1">* Geser kotak untuk merubah urutan minat Anda</p>
                                    </div>

                                    <div id="minat-list" class="space-y-3">
                                        @php
                                            // Ambil dari database, jika kosong baru pakai default list
                                            $saved_minat = $applicant->profile->minat;
                                            
                                            if (!$saved_minat || count($saved_minat) == 0) {
                                                $minat_list = [
                                                    'Marketing', 'Human Resources & People Development', 'Sales & Distribution',
                                                    'General Affairs', 'Produksi / Teknik', 'Administrasi', 'Quality Control',
                                                    'Finance & Accounting', 'Research & Development', 'Internal Audit', 'Purchasing',
                                                    'Information Technology', 'Supply Chain & Logistic'
                                                ];
                                            } else {
                                                $minat_list = $saved_minat;
                                            }
                                        @endphp

                                        @foreach($minat_list as $index => $item)
                                        <div class="flex items-center bg-gray-50 p-1 rounded-xl border-2 border-transparent hover:border-red-500 transition-all cursor-move group">
                                            <div class="flex items-center justify-center w-8 h-8 bg-white rounded-lg shadow-sm mr-4">
                                                <span class="text-xs font-black text-red-600 rank-number">{{ $index + 1 }}</span>
                                            </div>
                                            <span class="text-sm font-bold text-gray-700">{{ $item }}</span>
                                            <input type="hidden" name="minat_ordered[]" value="{{ $item }}">
                                            
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

                            {{-- 1. KELUARGA INTI --}}
                            <div class="mb-10">
                                <label class="text-[11px] font-black text-red-600 uppercase tracking-widest block mb-4">Keluarga Inti (Suami/Istri/Anak)</label>
                                
                                <template x-for="(item, index) in keluargaInti" :key="index">
                                    <div class="relative p-5 mb-4 bg-gray-50 rounded-3xl border border-gray-100 hover:border-red-200 transition-all shadow-sm">
                                        {{-- Tombol Hapus --}}
                                        <button type="button" @click="keluargaInti.splice(index, 1)" class="absolute -top-2 -right-2 bg-white text-gray-400 hover:text-red-600 shadow-md rounded-full p-1.5 border border-gray-100 transition-transform hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>

                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                                            {{-- Nama Lengkap --}}
                                            <div class="md:col-span-5">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Nama Lengkap</label>
                                                <input type="text" :name="'k_inti['+index+'][nama]'" 
                                                    x-model="item.nama" 
                                                    placeholder="Nama sesuai identitas" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>

                                            {{-- Hubungan --}}
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Hubungan</label>
                                                <input type="text" :name="'k_inti['+index+'][hubungan]'" 
                                                    x-model="item.hubungan" 
                                                    placeholder="Suami/Istri/Anak ke-1" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>

                                            {{-- Pendidikan Terakhir --}}
                                            <div class="md:col-span-4">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Pendidikan Terakhir</label>
                                                <select :name="'k_inti['+index+'][pendidikan]'" 
                                                        x-model="item.pendidikan" 
                                                        class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm appearance-none">
                                                    <option value="">Pilih Pendidikan</option>
                                                    <option>SD</option><option>SMP</option><option>SMA/SMK</option><option>D3</option><option>S1</option><option>S2</option><option>Tidak Sekolah</option>
                                                </select>
                                            </div>

                                            {{-- Tempat Lahir --}}
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tempat Lahir</label>
                                                <input type="text" :name="'k_inti['+index+'][tempat_lahir]'" 
                                                    x-model="item.tempat_lahir" 
                                                    placeholder="Kota Lahir" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>

                                            {{-- Tanggal Lahir --}}
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tanggal Lahir</label>
                                                <input type="date" :name="'k_inti['+index+'][tgl_lahir]'" 
                                                    x-model="item.tgl_lahir" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>

                                            {{-- Pekerjaan --}}
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Pekerjaan</label>
                                                <input type="text" :name="'k_inti['+index+'][pekerjaan]'" 
                                                    x-model="item.pekerjaan" 
                                                    placeholder="Cth: Karyawan Swasta" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>

                                            {{-- No HP --}}
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">No HP / WhatsApp</label>
                                                <input type="text" :name="'k_inti['+index+'][hp]'" 
                                                    x-model="item.hp" 
                                                    placeholder="0812..." 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <button type="button" @click="keluargaInti.push({nama:'', hubungan:'', tempat_lahir:'', tgl_lahir:'', pendidikan:'', pekerjaan:'', hp:''})" 
                                        class="mt-2 text-[10px] font-black text-red-600 uppercase tracking-widest bg-red-50 px-5 py-2.5 rounded-xl hover:bg-red-100 transition-all">
                                    + Tambah Anggota Keluarga
                                </button>
                            </div>

                            {{-- 2. KELUARGA KANDUNG --}}
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
                                                <input type="text" :name="'k_kandung['+index+'][nama]'" x-model="item.nama" placeholder="Nama sesuai identitas" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Hubungan</label>
                                                <input type="text" :name="'k_kandung['+index+'][hubungan]'" x-model="item.hubungan" placeholder="Ayah/Ibu/Kakak/Adik" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-4">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Pendidikan Terakhir</label>
                                                <select :name="'k_kandung['+index+'][pendidikan]'" x-model="item.pendidikan" class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm appearance-none">
                                                    <option value="">Pilih Pendidikan</option>
                                                    <option>SD</option><option>SMP</option><option>SMA/SMK</option><option>D3</option><option>S1</option><option>S2</option><option>Tidak Sekolah</option>
                                                </select>
                                            </div>

                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tempat Lahir</label>
                                                <input type="text" :name="'k_kandung['+index+'][tempat_lahir]'" x-model="item.tempat_lahir" placeholder="Kota lahir" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Tanggal Lahir</label>
                                                <input type="date" :name="'k_kandung['+index+'][tgl_lahir]'" x-model="item.tgl_lahir" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Pekerjaan</label>
                                                <input type="text" :name="'k_kandung['+index+'][pekerjaan]'" x-model="item.pekerjaan" placeholder="Cth: Karyawan Swasta" 
                                                    class="w-full mt-0.5 px-4 py-2.5 bg-white rounded-xl text-sm border-none focus:ring-2 focus:ring-red-500 outline-none shadow-sm">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase ml-1">No HP / WhatsApp</label>
                                                <input type="text" :name="'k_kandung['+index+'][hp]'" x-model="item.hp" placeholder="0812..." 
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
                                                    {{-- Gunakan is_current_edu agar konsisten --}}
                                                    <input type="number" 
                                                    :name="'pendidikan_formal['+index+'][tahun_lulus]'" 
                                                    x-model="item.tahun_lulus"
                                                    {{-- HANYA disable jika nilainya BENAR-BENAR '1' atau true --}}
                                                    :disabled="item.is_current_edu == '1' || item.is_current_edu === true"
                                                    :placeholder="(item.is_current_edu == '1' || item.is_current_edu === true) ? '---' : '2024'" 
                                                    :class="(item.is_current_edu == '1' || item.is_current_edu === true) ? 'bg-gray-100 text-gray-300' : 'bg-white'"
                                                    class="w-full mt-1 px-4 py-2.5 rounded-lg text-sm border-none focus:ring-2 focus:ring-red-500 outline-none text-center shadow-sm transition-all">
                                                </div>

                                                <div class="flex items-center h-10">
                                                    <label class="inline-flex items-center cursor-pointer group">
                                                        {{-- 1. Hidden input supaya kalau gak dicentang, tetap kirim angka 0 ke database --}}
                                                        <input type="hidden" :name="'pendidikan_formal['+index+'][is_current_edu]'" value="0">
                                                        
                                                        {{-- 2. Checkbox --}}
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
                                            <input type="text" :name="'pendidikan_informal['+index+'][kursus]'" x-model="item.kursus" placeholder="Contoh: Digital Marketing / Welder Certification" class="w-full px-4 py-3 bg-white rounded-xl text-sm border-2 border-transparent focus:border-red-500 outline-none">
                                        </div>
                                        <div class="flex-1 w-full">
                                            <label class="text-[9px] font-bold text-gray-400 uppercase ml-1">Penyelenggara</label>
                                            <input type="text" :name="'pendidikan_informal['+index+'][penyelenggara]'" x-model="item.penyelenggara" placeholder="Nama Lembaga" class="w-full px-4 py-3 bg-white rounded-xl text-sm border-2 border-transparent focus:border-red-500 outline-none">
                                        </div>
                                        <div class="w-full md:w-32">
                                            <label class="text-[9px] font-bold text-gray-400 uppercase ml-1">Tahun</label>
                                            <input type="number" :name="'pendidikan_informal['+index+'][tahun]'" x-model="item.tahun" placeholder="2023" class="w-full px-4 py-3 bg-white rounded-xl text-sm border-2 border-transparent focus:border-red-500 outline-none">
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
                                <div class="bg-gray-50 p-6 rounded-[2rem] mb-6 relative border-2 border-transparent hover:border-red-100 transition-all">
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
                                                    {{-- Gunakan pengecekan eksplisit agar tidak macet --}}
                                                    :disabled="item.masih_bekerja == '1' || item.masih_bekerja === true"
                                                    :class="(item.masih_bekerja == '1' || item.masih_bekerja === true) ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white'"
                                                    class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm transition-all">
                                            </div>
                                        </div>

                                        {{-- Ceklis Masih Bekerja --}}
                                        <div class="md:col-span-2 flex items-center px-1">
                                            <label class="inline-flex items-center cursor-pointer group">
                                                {{-- Hidden input supaya data 0 tetap terkirim --}}
                                                <input type="hidden" :name="'pengalaman_kerja['+index+'][masih_bekerja]'" value="0">
                                                
                                                <input type="checkbox" :name="'pengalaman_kerja['+index+'][masih_bekerja]'" 
                                                    x-model="item.masih_bekerja"
                                                    {{-- Paksa sinkronisasi visual --}}
                                                    :checked="item.masih_bekerja == '1' || item.masih_bekerja === true"
                                                    {{-- Logika saat berubah: set ke '1'/'0' dan bersihkan field terkait --}}
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
                                                placeholder="Nama & No HP (Cth: Bpk Budi - 0812...)" 
                                                class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm">
                                        </div>

                                        {{-- Alasan Keluar --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Alasan Keluar / Resign</label>
                                            <input type="text" :name="'pengalaman_kerja['+index+'][alasan]'" 
                                                x-model="item.alasan"
                                                {{-- Logika Disable yang aman --}}
                                                :disabled="item.masih_bekerja == '1' || item.masih_bekerja === true"
                                                :class="(item.masih_bekerja == '1' || item.masih_bekerja === true) ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white'"
                                                :placeholder="(item.masih_bekerja == '1' || item.masih_bekerja === true) ? 'Masih aktif bekerja' : 'Sebutkan alasan yang jelas'" 
                                                maxlength="150" 
                                                class="w-full px-4 py-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-red-500 shadow-sm transition-all">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <button type="button" 
                                    @click="if(pengalamanKerja.length < 3) pengalamanKerja.push({perusahaan: '', jabatan: '', divisi: '', tanggal_masuk: '', tanggal_keluar: '', masih_bekerja: '0', gaji: '', fasilitas: '', kontak_referensi: '', alasan: ''})" 
                                    x-show="pengalamanKerja.length < 3" 
                                    class="w-full py-4 border-2 border-dashed border-gray-200 rounded-2xl text-gray-400 font-bold hover:border-red-300 hover:text-red-500 transition-all">
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
                                    <div class="space-y-6">
                                        @foreach([
                                            'doc_foto'   => ['label' => 'PAS FOTO TERBARU', 'limit' => 2, 'accept' => 'image/*, .pdf'],
                                            'doc_cv'     => ['label' => 'CV', 'limit' => 5, 'accept' => 'image/*, .pdf'],
                                            'doc_ktp'    => ['label' => 'KTP', 'limit' => 2, 'accept' => 'image/*, .pdf'],
                                            'doc_ijazah' => ['label' => 'IJAZAH / TRANSKRIP NILAI', 'limit' => 2, 'accept' => 'image/*, .pdf']
                                        ] as $field => $info)
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <p class="text-[10px] font-bold text-gray-400">{{ $info['label'] }} (Maks {{ $info['limit'] }}MB)</p>
                                                
                                                {{-- Label Ukuran File Baru --}}
                                                <template x-if="fileSizes['{{ $field }}']">
                                                    <span class="text-[9px] px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-bold" x-text="'Baru: ' + fileSizes['{{ $field }}']"></span>
                                                </template>
                                            </div>
                                            
                                            {{-- Input File dengan atribut ACCEPT --}}
                                            <input type="file" 
                                                name="{{ $field }}" 
                                                accept="{{ $info['accept'] }}"
                                                @change="validateFile($event, {{ $info['limit'] }})"
                                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-700">

                                            {{-- History File LAMA (Tetap Muncul) --}}
                                            @if($applicant->profile && $applicant->profile->$field)
                                                <div class="mt-2 flex items-center gap-1 text-[10px] text-green-600 font-bold italic bg-green-50 p-1.5 rounded-md border border-green-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                    Sudah ada file
                                                    <a href="{{ asset('storage/' . $applicant->profile->$field) }}" target="_blank" class="underline hover:text-green-800 ml-auto">(Lihat File Lama)</a>
                                                </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Sisi Kanan: Pendukung --}}
                                <div class="space-y-4">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Pendukung (Jika ada)</label>
                                    <div class="space-y-4">
                                        @foreach([
                                            'doc_sim' => 'SIM',
                                            'doc_npwp' => 'NPWP',
                                            'doc_bpjs_kes' => 'BPJS KES',
                                            'doc_bpjs_tk' => 'BPJS TK',
                                            'doc_lain' => 'LAINNYA'
                                        ] as $field => $label)
                                        <div class="border-b border-gray-100 pb-3">
                                            <div class="flex items-center gap-2">
                                                <input type="file" name="{{ $field }}" @change="validateFile($event, 2)" class="flex-1 text-xs">
                                                <span class="text-[10px] font-bold text-gray-300 w-24 text-right">{{ $label }}</span>
                                            </div>

                                            {{-- History File LAMA --}}
                                            @if($applicant->profile && $applicant->profile->$field)
                                                <div class="mt-2 text-[9px] text-blue-500 font-bold italic flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z" /></svg>
                                                    Terunggah: <a href="{{ asset('storage/' . $applicant->profile->$field) }}" target="_blank" class="underline ml-1 truncate max-w-[150px]">{{ basename($applicant->profile->$field) }}</a>
                                                </div>
                                            @endif
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
                                <button type="submit" onclick="this.innerHTML='Menyimpan...'; this.classList.add('opacity-50')" class="h-auto md:h-14 py-4 flex-[2] h-14 bg-green-600 rounded-2xl text-white font-bold shadow-lg shadow-green-200 hover:bg-green-700 transition-all">Simpan Perubahan</button>
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