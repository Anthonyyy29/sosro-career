@extends('guest.templates.index')
@section('content')
    <div class="min-h-[calc(100vh-64px)] flex flex-col items-center justify-center px-4 py-12">
        <div class="w-full sm:max-w-md bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2rem] border border-gray-100 p-8 md:p-10">
            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 text-center mb-4">Verifikasi Email</h2>

            <div class="mb-5 text-sm text-gray-600 text-center leading-relaxed">
                {{ __('Terima kasih sudah mendaftar! Sebelum memulai, silakan verifikasi alamat email kamu melalui link yang baru saja kami kirim.') }}
            </div>

            {{-- Menampilkan email tujuan, agar user sadar kalau ada typo --}}
            <div class="mb-6 bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-center">
                <p class="text-xs text-gray-400 font-medium mb-1">Link dikirim ke</p>
                <p class="text-sm font-bold text-gray-800 break-all">{{ auth()->user()->email }}</p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 flex items-start gap-2 bg-green-50 text-green-700 text-sm font-medium rounded-xl px-4 py-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ __('Link verifikasi baru telah dikirim ke alamat email kamu.') }}</span>
                </div>
            @endif

            @if (session('status') == 'email-changed')
                <div class="mb-6 flex items-start gap-2 bg-green-50 text-green-700 text-sm font-medium rounded-xl px-4 py-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ __('Alamat email berhasil diperbarui. Link verifikasi baru sudah dikirim.') }}</span>
                </div>
            @endif

            <div class="space-y-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full group relative flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-300 bg-red-600 rounded-2xl hover:bg-red-700 hover:shadow-xl hover:shadow-red-200 active:scale-95">
                        {{ __('Kirim Ulang Email Verifikasi') }}
                    </button>
                </form>

                {{-- Form ganti alamat email --}}
                <details class="group border border-gray-100 rounded-2xl overflow-hidden" @if($errors->any()) open @endif>
                    <summary class="cursor-pointer list-none px-5 py-4 text-sm font-bold text-gray-500 hover:text-red-600 transition-colors flex items-center justify-between">
                        <span>{{ __('Salah memasukkan email?') }}</span>
                        <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>

                    <div class="px-5 pb-5 pt-1">
                        <p class="text-xs text-gray-400 leading-relaxed mb-4">
                            {{ __('Masukkan alamat email yang benar. Kami akan mengirim ulang link verifikasi ke alamat tersebut.') }}
                        </p>

                        <form method="POST" action="{{ route('verification.change-email') }}" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="nama@email.com"
                                class="w-full px-5 py-3.5 text-sm bg-gray-50 border @error('email') border-red-300 @else border-gray-200 @enderror rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                            >

                            @error('email')
                                <p class="text-xs font-medium text-red-600 px-1">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="w-full flex items-center justify-center px-8 py-3.5 text-sm font-bold text-red-600 bg-red-50 rounded-2xl hover:bg-red-100 transition-all duration-300 active:scale-95">
                                {{ __('Perbarui & Kirim Ulang') }}
                            </button>
                        </form>
                    </div>
                </details>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center py-3 text-sm font-bold text-gray-400 hover:text-red-600 transition-colors">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection