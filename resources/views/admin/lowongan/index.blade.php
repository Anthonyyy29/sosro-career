@extends('admin.layout')

@section('content')

<div x-data="{ 
    open: false,
    editOpen: false,
    editId: null,
    form: {},
    editAction: '' 
    }" 
    class="bg-white p-6 rounded-lg shadow-md">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-gray-800">Manajemen Lowongan</h2>

        <button @click="open = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            + Buat Lowongan
        </button>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto w-full">
        <table class="min-w-full text-left text-sm" id="table">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="py-3 px-4 whitespace-nowrap">No</th>
                    <th class="py-3 px-4 whitespace-nowrap">Kode</th>
                    <th class="py-3 px-4 whitespace-nowrap">Judul</th>
                    <th class="py-3 px-4 whitespace-nowrap">Kategori</th>
                    <th class="py-3 px-4 whitespace-nowrap">Bidang</th>
                    <th class="py-3 px-4 whitespace-nowrap">Status</th>
                    <th class="py-3 px-4 whitespace-nowrap">Batas Lamar</th>
                    <th class="py-3 px-4 whitespace-nowrap text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
    @foreach ($lowongan as $item)
    {{-- Perubahan di baris bawah ini --}}
    <tr class="border-b odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
        
        <td class="py-3 px-4">{{ $loop->iteration }}</td>
        <td class="py-3 px-4 font-mono">{{ $item->kode_lowongan }}</td>
        <td class="py-3 px-4">{{ $item->judul_lowongan }}</td>
        <td class="py-3 px-4">{{ $item->kategori }}</td>
        <td class="py-3 px-4">{{ $item->bidang }}</td>
        <td class="py-3 px-4 capitalize">{{ $item->status_lowongan }}</td>
        <td class="py-3 px-4 ">{{ \Carbon\Carbon::parse($item->tanggal_akhir)->locale('id')->translatedFormat('d F Y') }}</td>
        
        <td class="py-3 px-4 text-center">
            {{-- Tombol Edit --}}
            <button
                @click="
                    editOpen = true;
                    editId = {{ $item->id }};
                    editAction = '{{ route('admin.lowongan.update', $item->id) }}';
                    form = {{ $item->toJson() }};
                "
                class="text-blue-600 hover:underline mr-2">
                Edit
            </button>

            {{-- Tombol Hapus --}}
            <form action="{{ route('admin.lowongan.destroy', $item->id) }}"
                method="POST" class="inline"
                onsubmit="return confirm('Hapus data ini?')">
                @csrf @method('DELETE')
                <button class="text-red-600 hover:underline">Hapus</button>
            </form>

            {{-- Tombol Status --}}
            <form method="POST"
                action="{{ route('admin.lowongan.toggle-status', $item->id) }}"
                class="inline">
                @csrf @method('PATCH')
                <button
                    class="px-2 py-1 text-xs font-semibold rounded hover:text-black
                    {{ $item->status_lowongan === 'aktif' ? 'bg-green-600 text-white' : 'bg-gray-400 text-white' }}">
                    {{ $item->status_lowongan === 'aktif' ? 'Aktif' : 'Non-Aktif' }}
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</tbody>
        </table>
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
                    <select name="kategori" class="input">
                        <option value="">Pilih Kategori</option>
                        <option value="Profesional">Profesional</option>
                        <option value="MT">Management Trainee</option>
                        <option value="Magang">Magang</option>
                    </select>

                    <!-- BIDANG -->
                    <select name="bidang" class="input">
                        <option value="">Pilih Bidang</option>
                        <option value="IT">Teknologi Informasi (IT)</option>
                        <option value="HC">Human Capital</option>
                        <option value="Finance">Finance</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Sales">Sales & Distribution</option>
                        <option value="Pabrik">Pabrik</option>
                    </select>

                    <select name="tipe_lowongan" class="input">
                        <option value="">Pilih Tipe Lowongan</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Kontrak">Kontrak</option>
                    </select>

                    <input class="input" name="penempatan_cabang" placeholder="Penempatan">
                    <input class="input" name="lokasi_kerja" placeholder="Lokasi Kerja">
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
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg">
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

                    <select name="kategori" class="input" x-model="form.kategori">
                        <option value="">Pilih Kategori</option>
                        <option value="Profesional">Profesional</option>
                        <option value="MT">Management Trainee</option>
                        <option value="Magang">Magang</option>
                    </select>

                    <select name="bidang" class="input" x-model="form.bidang">
                        <option value="">Pilih Bidang</option>
                        <option value="IT">Teknologi Informasi (IT)</option>
                        <option value="HC">Human Capital</option>
                        <option value="Finance">Finance</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Sales">Sales & Distribution</option>
                        <option value="Pabrik">Pabrik</option>
                    </select>

                    <select name="tipe_lowongan" class="input" x-model="form.tipe_lowongan">
                        <option value="">Pilih Tipe Lowongan</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Kontrak">Kontrak</option>
                    </select>

                    <input class="input" name="penempatan_cabang" x-model="form.penempatan_cabang">
                    <input class="input" name="lokasi_kerja" x-model="form.lokasi_kerja">

                    <input type="text" class="input bg-gray-100" :value="form.tanggal_mulai" disabled>

                    <input type="date" class="input" name="tanggal_akhir" x-model="form.tanggal_akhir">
                    
                    <textarea class="input" rows="5" name="jobdesk" x-model="form.jobdesk"></textarea>
                    <textarea class="input" rows="5" name="kualifikasi" x-model="form.kualifikasi"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" @click="editOpen = false"
                            class="px-4 py-2 border rounded-lg">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg">
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

@endsection
