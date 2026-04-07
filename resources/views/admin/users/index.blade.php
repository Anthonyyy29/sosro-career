@extends('admin.layout')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h1>
            <p class="text-sm text-gray-500">Kelola dan pantau status pengguna.</p>
        </div>
        <button onclick="document.getElementById('modalUser').classList.remove('hidden')" 
                class="inline-flex items-center justify-center px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-red-200 transition-all active:scale-95 gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
            <span class="hidden sm:inline">Tambah Pengguna</span>
        </button>
    </div>

    <div class="overflow-hidden">
        <div class="overflow-x-auto">

            <table id="table" class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">No</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Nama</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Email</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Role</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Status Akun</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Biodata</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allUsers as $user)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="py-4 font-semibold text-gray-700">{{ $user->name }}</td>
                        <td class="py-4 text-gray-500">{{ $user->email }}</td>
                        <td class="py-4">
                            @if($user->role == 'superadmin')
                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-lg text-[10px] font-bold uppercase">
                                    Super Admin
                                </span>
                            @elseif($user->role == 'admin')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-[10px] font-bold uppercase">
                                    Admin Cabang
                                </span>
                            @else
                                <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-lg text-[10px] font-bold uppercase">
                                    Pelamar
                                </span>
                            @endif
                        </td>
                        <td class="py-4">
                            <span class="text-[11px] text-gray-400">Terdaftar: {{ $user->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user->role == 'pelamar')
                                @if($user->has_profile)
                                    <div class="flex items-center gap-2 justify-center">
                                        {{-- <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Lengkap</span> --}}
                                        {{-- <span class="text-green-400 text-[10px] italic font-medium tracking-tight">Lengkap</span> --}}

                                        {{-- Tombol Lihat PDF --}}
                                        <a href="{{ route('admin.users.downloadProfile', $user->applicant_id) }}" 
                                        target="_blank" 
                                        class="text-blue-500 hover:text-blue-700 hover:underline transition-colors text-[11px] italic font-medium tracking-tight" title="Lihat Biodata"> Lihat Biodata
                                            {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg> --}}
                                        </a>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-[10px] italic italic font-medium tracking-tight">Belum Isi Biodata</span>
                                @endif
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="py-4 text-center">
                        {{-- Jika ID user di baris ini SAMA dengan ID admin yang login, sembunyikan tombol hapusnya --}}
                            @if($user->id == auth()->id() && $user->source_table == 'admins')
                                <span class="text-xs text-gray-400 italic">Akun Anda</span>
                            @else
                                <form action="{{ route('admin.users.destroy', $user->id) }}?source={{ $user->source_table }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

{{-- Modal Create User --}}
<div id="modalUser" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-40"></div>

        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800 tracking-tight">Buat Akun Baru</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all" placeholder="Masukkan nama...">
                    </div>

                    <div>
                        <label class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest ml-1">Alamat Email</label>
                        <input type="email" name="email" required class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all" placeholder="email@contoh.com">
                    </div>

                    <div>
                        <label class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" required minlength="8" 
                            class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all" 
                            placeholder="Minimal 8 karakter" title="Password minimal 8 karakter">
                    </div>
                    
                    <div>
                        <label class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest ml-1">Tipe Pengguna (Role)</label>
                        <select name="role" required class="w-full px-5 py-3 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 outline-none transition-all appearance-none">
                            <option value="pelamar">Pelamar</option>
                            <option value="admin">Admin Cabang</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="closeModal()" class="flex-1 px-6 py-3 font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition-all">Batal</button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-200 transition-all">Simpan Akun</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalUser').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('modalUser').classList.add('hidden');
    }
</script>
@endsection