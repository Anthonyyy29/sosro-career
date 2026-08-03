@extends('admin.layout')

@section('content')

<div x-data="{ 
    open: false,
    editOpen: false,
    editId: null,
    form: {},
    editAction: '' 
    }" git 
    class="bg-white p-6 rounded-xl shadow-md">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Lowongan</h1>
            <p class="text-sm text-gray-500">Kelola dan pantau status publikasi lowongan kerja.</p>
        </div>

        <button @click="open = true" class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-200 transition-all active:scale-95 gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">Buat Lowongan</span>
        </button>
    </div>

    <!-- TABLE -->
    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm" id="table">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">No</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Informasi Lowongan</th>
                        <th class="py-4 px-6 text-center font-bold text-gray-500 uppercase tracking-wider text-[11px]">Kandidat</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Terbuat</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Batas Waktu</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Status</th>
                        <th class="py-4 px-6 text-center font-bold text-gray-500 uppercase tracking-wider text-[11px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($lowongan as $item)
                    <tr onclick="window.location='{{ route('admin.lowongan.applicants', $item->id) }}'"
                        class="group hover:bg-blue-50/40 cursor-pointer transition-all duration-100">

                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $loop->iteration }}</td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-3">
                                <div class="hidden sm:flex flex-col items-center justify-center w-10 h-10 rounded-lg bg-gray-50 text-gray-400 font-mono text-[10px] border border-gray-100">
                                    {{ $item->kode_lowongan }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800 group-hover:text-blue-700 transition-colors">{{ $item->judul_lowongan }}</span>
                                    <span class="text-[11px] text-gray-400 font-medium tracking-wide uppercase mt-0.5">{{ $item->kategori }} • {{ $item->bidang }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="text-base font-bold text-gray-800">{{ $item->applications_count }}</span>
                                <span class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Pelamar</span>
                            </div>
                        </td>
                        
                        {{-- Siapa Pembuat lowongan --}}
                        <td class="px-6 py-4"> 
                            <div class="flex flex-col"> 
                                <span class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->locale('id')->translatedFormat('d M Y') }}</span> 
                                @if($item->created_by == '1') 
                                    <span class="text-[9px] text-green-400 font-bold uppercase">HO</span> 
                                @else 
                                    <span class="text-[9px] text-green-400 font-bold uppercase">{{ $item->admin->name }}</span> 
                                @endif 
                            </div> 
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($item->tanggal_akhir)->locale('id')->translatedFormat('d M Y') }}</span>
                                @if(\Carbon\Carbon::parse($item->tanggal_akhir)->isPast())
                                    <span class="text-[10px] text-red-500 font-bold uppercase">Sudah Berakhir</span>
                                @else
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">{{ \Carbon\Carbon::parse($item->tanggal_akhir)->diffForHumans() }}</span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if(Auth::user()->role === 'superadmin')
                                {{-- Jika Superadmin: Tampilkan Form Toggle (Bisa Klik) --}}
                                <form method="POST" action="{{ route('admin.lowongan.toggle-status', $item->id) }}" onclick="event.stopPropagation()">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="Ubah Status" 
                                        class="group relative inline-flex items-center gap-2 px-3 py-1.5 rounded-full border transition-all duration-300 ease-in-out
                                        {{ $item->status_lowongan === 'aktif' 
                                            ? 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100 hover:shadow-sm' 
                                            : 'bg-slate-50 border-slate-200 text-slate-500 hover:bg-slate-100' }}">
                                        
                                        <span class="relative flex h-2 w-2">
                                            @if($item->status_lowongan === 'aktif')
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            @endif
                                            <span class="relative inline-flex rounded-full h-2 w-2 {{ $item->status_lowongan === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        </span>
                                        
                                        <span class="text-[10px] tracking-wider font-bold uppercase">
                                            {{ $item->status_lowongan }}
                                        </span>
                                    </button>
                                </form>
                            @else
                                {{-- Jika Admin Biasa: Tampilkan Badge Saja (Tidak Bisa Klik) --}}
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border opacity-70 cursor-not-allowed
                                    {{ $item->status_lowongan === 'aktif' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-500' }}">
                                    <span class="relative flex h-2 w-2">
                                        <span class="relative inline-flex rounded-full h-2 w-2 {{ $item->status_lowongan === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    </span>
                                    <span class="text-[10px] tracking-wider font-bold uppercase">
                                        {{ $item->status_lowongan }}
                                    </span>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2" onclick="event.stopPropagation()">
                                <button @click.stop="
                                    editOpen = true;
                                    editId = {{ $item->id }};
                                    editAction = '{{ route('admin.lowongan.update', $item->id) }}';
                                    form = {{ $item->toJson() }};
                                    window.dispatchEvent(new CustomEvent('fill-editors', { detail: form }));
                                " title="Edit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition shadow-sm border border-transparent hover:border-blue-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>

                                <form action="{{ route('admin.lowongan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus lowongan ini?')">
                                    @csrf @method('DELETE')
                                    <button title="Hapus" type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-xl transition shadow-sm border border-transparent hover:border-red-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= MODAL CREATE ================= -->
    <div x-show="open" x-transition.opacity x-cloak
         class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div @click.outside="open = false"
             class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6
                    max-h-[90vh] overflow-y-auto">

            <!-- MODAL HEADER -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Tambah Lowongan</h3>
                <button @click="open = false"
                        class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>

            <!-- FORM -->
            <form action="{{ route('admin.lowongan.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input class="input" name="judul_lowongan" placeholder="Judul Lowongan" required>

                    <!-- KATEGORI -->
                    <select name="kategori" class="input" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="Profesional">Profesional</option>
                        <option value="Management Trainee">Management Trainee</option>
                        <option value="Magang">Magang</option>
                    </select>

                    <!-- BIDANG -->
                    <select name="bidang" class="input" required>
                        <option value="" disabled selected>Pilih Bidang</option>
                        <option value="Administrasi">Administrasi</option>
                        <option value="Finance & Accounting">Finance & Accounting</option>
                        <option value="General Affairs">General Affairs</option>
                        <option value="Human Resources & People Development">Human Resources & People Development</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Internal Audit">Internal Audit</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Produksi / Teknik">Produksi / Teknik</option>
                        <option value="Purchasing">Purchasing</option>
                        <option value="Quality Control">Quality Control</option>
                        <option value="Research & Development">Research & Development</option>
                        <option value="Sales & Distribution">Sales & Distribution</option>
                        <option value="Supply Chain & Logistic">Supply Chain & Logistic</option>
                    </select>

                    <select name="tipe_lowongan" class="input" required>
                        <option value="" disabled selected>Pilih Tipe Lowongan</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Kontrak">Kontrak</option>
                    </select>

                    <select name="penempatan_cabang" class="input" required>
                        <option value="" disabled selected>Pilih Penempatan</option>
                        <option value="Kantor Pusat" class="font-bold">Kantor Pusat</option>
                        <optgroup label="KPW">
                            <option value="KPW Bali Nusra">KPW Bali Nusra</option>
                            <option value="KPW Jakarta Banten">KPW Jakarta Banten</option>
                            <option value="KPW Jawa Barat">KPW Jawa Barat</option>
                            <option value="KPW Jawa Tengah">KPW Jawa Tengah</option>
                            <option value="KPW Jawa Timur">KPW Jawa Timur</option>
                            <option value="KPW Kalimantan Sulawesi">KPW Kalimantan Sulawesi</option>
                            <option value="KPW Sumbagsel Babel">KPW Sumbagsel Babel</option>

                            <option value="KPW Sumut NAD - Sumbar Kepri">KPW Sumut NAD - Sumbar Kepri</option>
                        </optgroup>
                        <optgroup label="Pabrik">
                            <option value="Pabrik Cakung">Pabrik Cakung</option>
                            <option value="Pabrik Cibitung">Pabrik Cibitung</option>
                            <option value="Pabrik Deli Serdang">Pabrik Deli Serdang</option>
                            <option value="Pabrik Gianyar">Pabrik Gianyar</option>
                            <option value="Pabrik Mojokerto">Pabrik Mojokerto</option>
                            <option value="Pabrik Palembang">Pabrik Palembang</option>
                            <option value="Pabrik Pandaan">Pabrik Pandaan</option>
                            <option value="Pabrik Purbalingga">Pabrik Purbalingga</option>
                            <option value="Pabrik Sentul">Pabrik Sentul</option>
                            <option value="Pabrik Slawi">Pabrik Slawi</option>
                            <option value="Pabrik Ungaran">Pabrik Ungaran</option>
                        </optgroup>
                        <optgroup label="Lainnya">
                            <option value="Kebun">Kebun</option>
                            <option value="Poci Kreasi Mandiri">Poci Kreasi Mandiri</option>
                        </optgroup>
                    </select>

                    <input class="input" name="lokasi_kerja" placeholder="Lokasi Kerja" required>

                    <!-- Tanggal Mulai (AUTO) -->
                    <input type="text" class="input bg-gray-100 cursor-not-allowed" value="{{ now()->format('d M Y') }}" disabled>

                    <!-- Tanggal Akhir (INPUT) -->
                    <input type="date" class="input" name="tanggal_akhir" required>

                    <textarea class="input" rows="5" name="jobdesk" placeholder="Jobdesk - Pisahkan tiap poin dengan ENTER"></textarea>
                    <textarea class="input" rows="5" name="kualifikasi" placeholder="Kualifikasi - Pisahkan tiap poin dengan ENTER"></textarea>
                </div>


                <!-- ACTION -->
                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" @click="open = false"
                            class="px-4 py-2 border rounded-lg">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- ================= END MODAL ================= -->
    <!-- ================= MODAL EDIT ================= -->
    <div x-show="editOpen" x-transition.opacity x-cloak
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div @click.outside="editOpen = false"
            class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6
                    max-h-[90vh] overflow-y-auto">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Edit Lowongan</h3>
                <button @click="editOpen = false"
                        class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>

            <form :action="editAction" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <input class="input" name="judul_lowongan" x-model="form.judul_lowongan" required>

                    <select name="kategori" class="input" x-model="form.kategori" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="Profesional">Profesional</option>
                        <option value="Management Trainee">Management Trainee</option>
                        <option value="Magang">Magang</option>
                    </select>

                    <select name="bidang" class="input" x-model="form.bidang" required>
                        <option value="" disabled selected>Pilih Bidang</option>
                        <option value="Administrasi">Administrasi</option>
                        <option value="Finance & Accounting">Finance & Accounting</option>
                        <option value="General Affairs">General Affairs</option>
                        <option value="Human Resources & People Development">Human Resources & People Development</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Internal Audit">Internal Audit</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Produksi / Teknik">Produksi / Teknik</option>
                        <option value="Purchasing">Purchasing</option>
                        <option value="Quality Control">Quality Control</option>
                        <option value="Research & Development">Research & Development</option>
                        <option value="Sales & Distribution">Sales & Distribution</option>
                        <option value="Supply Chain & Logistic">Supply Chain & Logistic</option>
                    </select>

                    <select name="tipe_lowongan" class="input" x-model="form.tipe_lowongan" required>
                        <option value="" disabled selected>Pilih Tipe Lowongan</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Kontrak">Kontrak</option>
                    </select>

                    <select name="penempatan_cabang" class="input" x-model="form.penempatan_cabang" required>
                        <option value="" disabled selected>Pilih Penempatan</option>
                        <option value="Kantor Pusat" class="font-bold">Kantor Pusat</option>
                        <optgroup label="KPW">
                            <option value="KPW Bali Nusra">KPW Bali Nusra</option>
                            <option value="KPW Jakarta Banten">KPW Jakarta Banten</option>
                            <option value="KPW Jawa Barat">KPW Jawa Barat</option>
                            <option value="KPW Jawa Tengah">KPW Jawa Tengah</option>
                            <option value="KPW Jawa Timur">KPW Jawa Timur</option>
                            <option value="KPW Kalimantan Sulawesi">KPW Kalimantan Sulawesi</option>
                            <option value="KPW Sumbagsel Babel">KPW Sumbagsel Babel</option>
                            
                            <option value="KPW Sumut NAD - Sumbar Kepri">KPW Sumut NAD - Sumbar Kepri</option>
                        </optgroup>
                        <optgroup label="Pabrik">
                            <option value="Pabrik Cakung">Pabrik Cakung</option>
                            <option value="Pabrik Cibitung">Pabrik Cibitung</option>
                            <option value="Pabrik Deli Serdang">Pabrik Deli Serdang</option>
                            <option value="Pabrik Gianyar">Pabrik Gianyar</option>
                            <option value="Pabrik Mojokerto">Pabrik Mojokerto</option>
                            <option value="Pabrik Palembang">Pabrik Palembang</option>
                            <option value="Pabrik Pandaan">Pabrik Pandaan</option>
                            <option value="Pabrik Purbalingga">Pabrik Purbalingga</option>
                            <option value="Pabrik Sentul">Pabrik Sentul</option>
                            <option value="Pabrik Slawi">Pabrik Slawi</option>
                            <option value="Pabrik Ungaran">Pabrik Ungaran</option>
                        </optgroup>
                        <optgroup label="Lainnya">
                            <option value="Kebun">Kebun</option>
                            <option value="Poci Kreasi Mandiri">Poci Kreasi Mandiri</option>
                        </optgroup>
                    </select>
                    
                    <input class="input" name="lokasi_kerja" x-model="form.lokasi_kerja" required>

                    <input type="text" class="input bg-gray-100" :value="form.tanggal_mulai" disabled>

                    <input type="date" class="input" name="tanggal_akhir" x-model="form.tanggal_akhir">
                    
                    <div class="mb-4" wire:ignore> <label class="block mb-2 font-semibold">Jobdesk</label>
                        <textarea id="editor-jobdesk-edit" name="jobdesk" x-on:update-jobdesk.window="form.jobdesk = $event.detail"></textarea>
                    </div>

                    <div class="mb-4" wire:ignore>
                        <label class="block mb-2 font-semibold">Kualifikasi</label>
                        <textarea id="editor-kualifikasi-edit" name="kualifikasi" x-on:update-kualifikasi.window="form.kualifikasi = $event.detail"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" @click="editOpen = false"
                            class="px-4 py-2 border rounded-lg">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- ================= END MODAL EDIT ================= -->

</div>

<!-- STYLE -->
<style>
    .input {
        @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
               focus:outline-none focus:ring-2 focus:ring-blue-500;
    }
</style>

    {{-- textarea --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        let editors = {};

        function initEditor(selector, fieldName) {
            ClassicEditor
                .create(document.querySelector(selector), {
                    toolbar: ['bold', 'italic', 'underline', 'bulletedList', 'numberedList', 'undo', 'redo']
                })
                .then(editor => {
                    editors[fieldName] = editor;
                    
                    // Jika menggunakan Alpine.js (Modal Edit), sinkronkan data saat editor berubah
                    editor.model.document.on('change:data', () => {
                        const data = editor.getData();
                        // Update manual jika Anda menggunakan x-model Alpine
                        if (window.Alpine) {
                            // Mencari scope Alpine terdekat untuk mengupdate variabel form
                            const modal = document.querySelector('[x-data]');
                            if (modal && modal.__x) {
                                modal.__x.$data.form[fieldName] = data;
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        }

        // Inisialisasi untuk Modal Create
        document.addEventListener('DOMContentLoaded', () => {
            initEditor('textarea[name="jobdesk"]', 'jobdesk');
            initEditor('textarea[name="kualifikasi"]', 'kualifikasi');
        });

        // Khusus untuk Modal Edit: Karena Alpine.js mengisi data secara dinamis, 
        // kita perlu memantau saat modal edit terbuka untuk mengisi konten editor.
        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => {
                const editOpen = Alpine.store('editOpen'); // Sesuaikan jika Anda pakai store atau variable lokal
                // Jika Anda menggunakan variable 'editOpen' di x-data, 
                // pastikan konten editor di-update saat data form masuk.
            });
        });
    </script>

    {{-- edit --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        let editEditorJob, editEditorKual;

        document.addEventListener('DOMContentLoaded', () => {
            const config = {
                // Kita tambahkan Underline di sini
                toolbar: ['bold', 'italic', 'underline', 'bulletedList', 'numberedList', 'undo', 'redo']
            };

            // Init Editor Jobdesk
            ClassicEditor.create(document.querySelector('#editor-jobdesk-edit'), config)
                .then(editor => {
                    editEditorJob = editor;
                    editor.model.document.on('change:data', () => {
                        // Kirim data ke Alpine form.jobdesk
                        window.dispatchEvent(new CustomEvent('update-jobdesk', { detail: editor.getData() }));
                    });
                });

            // Init Editor Kualifikasi
            ClassicEditor.create(document.querySelector('#editor-kualifikasi-edit'), config)
                .then(editor => {
                    editEditorKual = editor;
                    editor.model.document.on('change:data', () => {
                        // Kirim data ke Alpine form.kualifikasi
                        window.dispatchEvent(new CustomEvent('update-kualifikasi', { detail: editor.getData() }));
                    });
                });
        });

        // Fungsi inilah yang menangkap data dari button Edit Anda
        window.addEventListener('fill-editors', (e) => {
            // Beri sedikit delay (100ms) agar modal terbuka dulu baru data diisi
            setTimeout(() => {
                if(editEditorJob) editEditorJob.setData(e.detail.jobdesk || '');
                if(editEditorKual) editEditorKual.setData(e.detail.kualifikasi || '');
            }, 100);
        });
    </script>
@endsection
