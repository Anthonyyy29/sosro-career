@extends('admin.layout')

@section('content')

<div class="p-6 bg-white rounded-xl shadow-md">
    <h1 class="text-2xl font-bold text-gray-800">Pesan Masuk</h1>
    
    <div class="bg-white rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="table">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">No</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Pengirim</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Tujuan</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Pesan</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Status/Petugas</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-500 uppercase tracking-wider text-[11px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $msg)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="p-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="p-4">
                            <div class="text-sm font-bold">{{ $msg->name }}</div>
                            <div class="text-xs text-gray-400">{{ $msg->email }}</div>
                            <div class="text-[10px] text-gray-400 mt-1">{{ $msg->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="p-4 text-sm">{{ $msg->city }}</td>
                        <td class="p-4 text-sm text-gray-600">
                            <button type="button" 
                                    onclick="showMessageModal(`{{ $msg->id }}`, `{{ addslashes($msg->message) }}`)"
                                    class="text-left italic hover:underline" title="Lihat Selengkapnya">
                                "{{ Str::limit($msg->message, 50) }}"
                            </button>
                        </td>
                        {{-- Kolom Status/Petugas --}}
                        <td class="p-4">
                            @if($msg->status === 'replied')
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">
                                    ✓ Terbalas
                                </span>
                                <div class="text-[9px] text-gray-400 mt-1">Oleh: {{ $msg->admin->name ?? 'Superadmin' }}</div>
                            @elseif($msg->cabang_id)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">
                                    Diteruskan
                                </span>
                                <div class="text-[9px] text-gray-400 mt-1 pl-1">{{ $msg->cabang->nama }}</div>
                            @else
                                <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase animate-pulse">
                                    Baru/Pending
                                </span>
                            @endif
                        </td>

                        {{-- Kolom Aksi --}}
                        <td class="p-4">
                            <div class="flex items-center gap-3">

                                {{-- FITUR TERUSKAN (Hanya Superadmin) --}}
                                @if(auth()->user()->role === 'superadmin')
                                    <form action="{{ route('admin.kontak.assign', $msg->id) }}" method="POST"
                                        x-data="{ cabangSelected: '{{ $msg->cabang_id ?? '' }}' }" class="flex items-center gap-1">
                                        @csrf
                                        <select name="cabang_id" x-model="cabangSelected" required
                                                class="text-[10px] border-gray-300 rounded p-1 w-28 focus:ring-red-500">
                                            <option value="" disabled>Teruskan ke...</option>
                                            @foreach($cabangs as $cabang)
                                                <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" :disabled="!cabangSelected"
                                                class="bg-gray-800 text-white text-[10px] px-2 py-1 rounded disabled:opacity-50">
                                            Teruskan
                                        </button>
                                    </form>
                                @endif

                                {{-- FITUR BALAS (Semua Role) --}}
                                <form action="{{ route('admin.kontak.mark-replied', $msg->id) }}" method="POST" target="_blank" class="inline">
                                    @csrf
                                    <a href="mailto:{{ $msg->email }}?subject=Balasan Kontak Sosro Career - {{ $msg->name }}&body=Halo {{ $msg->name }},%0D%0A%0D%0AMenanggapi pesan Anda: '{{ addslashes($msg->message) }}'%0D%0A%0D%0A[Tulis Jawaban Di Sini]" 
                                    onclick="this.closest('form').submit()"
                                    class="p-1.5 text-blue-500 hover:text-blue-700 p-1 rounded-lg transition" title="Balas via Email">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </a>
                                </form>

                                {{-- FITUR HAPUS (Semua Role - Tapi Admin hanya bisa hapus yang ditugaskan ke cabangnya) --}}
                                @if(auth()->user()->role === 'superadmin' || auth()->user()->cabang_id === $msg->cabang_id)
                                    <form action="{{ route('admin.kontak.destroy', $msg->id) }}" method="POST" 
                                        onsubmit="return confirm('Hapus pesan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-lg hover:bg-red-50" title="Hapus Pesan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- Modal Lihat Pesan --}}
            <div id="messageModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50" onclick="closeMessageModal()">
                <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4" onclick="event.stopPropagation()">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Isi Pesan:</h3>
                        <button onclick="closeMessageModal()" class="text-gray-500 hover:text-gray-800">&times;</button>
                    </div>
                    <div class="max-h-96 overflow-y-auto text-gray-700 whitespace-pre-wrap" id="messageContent"></div>
                    <div class="mt-4 text-right">
                        <button onclick="closeMessageModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Tutup</button>
                    </div>
                </div>
            </div>

            <script>
            function showMessageModal(id, message) {
                document.getElementById('messageContent').innerText = message;
                document.getElementById('messageModal').classList.remove('hidden');
                document.getElementById('messageModal').classList.add('flex');
            }
            function closeMessageModal() {
                document.getElementById('messageModal').classList.add('hidden');
                document.getElementById('messageModal').classList.remove('flex');
            }
            </script>
            <div class="p-4">
                {{ $messages->links() }}
            </div>
        </div>

        @if($messages->isEmpty())
            <div class="py-5 flex flex-col items-center justify-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 0 1-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 0 0 1.183 1.981l6.478 3.488m8.839 2.51-4.66-2.51m0 0-1.023-.55a2.25 2.25 0 0 0-2.134 0l-1.022.55m0 0-4.661 2.51m16.5 1.615a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V8.844a2.25 2.25 0 0 1 1.183-1.981l7.5-4.039a2.25 2.25 0 0 1 2.134 0l7.5 4.039a2.25 2.25 0 0 1 1.183 1.98V19.5Z" />
                </svg>
                <p class="text-medium">Belum ada pesan.</p>
            </div>
        @endif
    </div>
</div>
@endsection