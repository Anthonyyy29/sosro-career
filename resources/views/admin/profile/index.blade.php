@extends('admin.layout')

@section('content')

<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Profil Saya</h1>
        <p class="text-gray-500 text-sm">Informasi detail akun administrator Anda yang terdaftar di sistem.</p>
    </div>

    {{-- MAIN PROFILE CARD --}}
    <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden p-2">
        <div class="flex flex-col lg:flex-row gap-10 p-6 lg:p-10">
            
            {{-- SISI KIRI: AVATAR --}}
            <div class="flex flex-col items-center lg:w-1/3 border-b lg:border-b-0 lg:border-r border-gray-50 pb-8 lg:pb-0 lg:pr-10">
                <div class="relative group mb-6">
                    <img id="avatar-preview" 
                         src="{{ auth()->user()->photo ? asset('storage/photos/' . auth()->user()->photo) . '?' . time() : asset('assets/images/images.png') }}"
                         class="w-40 h-40 rounded-xl border-4 border-gray-50 shadow-md object-cover bg-white transition-transform duration-300 group-hover:scale-[1.02]" 
                         alt="Admin Avatar">
                    
                    {{-- Form Upload Foto --}}
                    <form action="{{ route('admin.profile.upload-photo') }}" method="POST" enctype="multipart/form-data" id="photo-form">
                        @csrf
                        <input type="file" name="photo" id="photo-input" class="hidden" accept="image/*" onchange="this.form.submit()">
                        <button type="button" onclick="document.getElementById('photo-input').click()" class="absolute -bottom-2 -right-2 bg-red-600 text-white p-2.5 rounded-xl shadow-lg hover:bg-red-700 transition-all active:scale-90" title="Ubah Foto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-800 leading-tight">{{ auth()->user()->name }}</h2>
                    <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 bg-red-50 text-red-600 text-[11px] font-medium uppercase tracking-widest rounded-lg border border-red-100">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zM10 5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" clip-rule="evenodd" /></svg>
                        Administrator
                    </span>
                </div>
            </div>

            {{-- SISI KANAN: DETAIL INFORMASI --}}
            <div class="lg:w-2/3 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-[0.2em]">Detail Informasi</h3>
                    </div>
                    <p class="border-b border-red-50 w-full mb-2.5"></p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        {{-- Item 1 --}}
                        <div class="space-y-1.5 group">
                            <label class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                            <div class="flex items-center gap-3 p-3.5 bg-gray-50 rounded-2xl border border-transparent group-hover:border-gray-200 group-hover:bg-white transition-all">
                                <div class="p-2 bg-white rounded-xl shadow-sm group-hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                            </div>
                        </div>

                        {{-- Item 2 --}}
                        <div class="space-y-1.5 group">
                            <label class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">Email Perusahaan</label>
                            <div class="flex items-center gap-3 p-3.5 bg-gray-50 rounded-2xl border border-transparent group-hover:border-gray-200 group-hover:bg-white transition-all">
                                <div class="p-2 bg-white rounded-xl shadow-sm group-hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        {{-- Item 3 --}}
                        <div class="space-y-1.5 group">
                            <label class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">Role/Peran</label>
                            <div class="flex items-center gap-3 p-3.5 bg-gray-50 rounded-2xl border border-transparent group-hover:border-gray-200 group-hover:bg-white transition-all">
                                <div class="p-2 bg-white rounded-xl shadow-sm group-hover:text-red-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->role }}</p>
                            </div>
                        </div>

                        {{-- Item 4 --}}
                        <div class="space-y-1.5 group">
                            <label class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">Dibuat Pada</label>
                            <div class="flex items-center gap-3 p-3.5 bg-gray-50 rounded-2xl border border-transparent group-hover:border-gray-200 group-hover:bg-white transition-all">
                                <div class="p-2 bg-white rounded-xl shadow-sm group-hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->created_at->format('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection