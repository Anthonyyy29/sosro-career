<x-app-layout>
    <div class="min-h-[85vh] flex items-center justify-center bg-gray-50/30 px-4 py-12">
        <div class="w-full max-w-lg bg-white shadow-[0_20px_60px_rgba(0,0,0,0.07)] rounded-2xl overflow-hidden border border-gray-100/50">
            
            <div class="p-8 md:p-12">
                <div class="text-center mb-10">
                    <div class="inline-block p-4 bg-red-50 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
                        Buat <span class="text-red-600">Akun Baru</span>
                    </h2>
                    <p class="text-gray-500 mt-2 font-medium">Mulai langkah karier Anda bersama Sosro.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="font-bold text-gray-700 ml-1" />
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <x-text-input id="name" class="block w-full pl-11 pr-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition duration-200" type="text" name="name" :value="old('name')" required autofocus placeholder="Nama sesuai identitas" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Alamat Email')" class="font-bold text-gray-700 ml-1" />
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002-2z" /></svg>
                            </div>
                            <x-text-input id="email" class="block w-full pl-11 pr-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition duration-200" type="email" name="email" :value="old('email')" required placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="font-bold text-gray-700 ml-1" />
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </div>
                                <x-text-input id="password" class="block w-full pl-11 pr-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition duration-200" type="password" name="password" required placeholder="••••••••" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="font-bold text-gray-700 ml-1" />
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                </div>
                                <x-text-input id="password_confirmation" class="block w-full pl-11 pr-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition duration-200" type="password" name="password_confirmation" required placeholder="••••••••" />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <x-input-error :messages="$errors->get('password')" class="text-xs" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="text-xs" />
                    </div>

                    <div class="flex flex-col space-y-4 pt-4">
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-4 rounded-2xl font-bold shadow-xl shadow-red-200 active:scale-[0.98] transition-all duration-150 flex justify-center items-center tracking-wide">
                            <span>Daftarkan Akun</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </button>

                        <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 mt-2">
                            <span>Sudah terdaftar?</span>
                            <a class="font-bold text-red-600 hover:text-red-700 hover:underline decoration-2 underline-offset-4 transition-all" href="{{ route('login') }}">
                                {{ __('Masuk di sini') }}
                            </a>
                        </div>
                    </div>
                    {{-- Footer Note --}}
                    <p class="mt-8 text-center text-xs text-gray-400">
                        &copy; {{ date('Y') }} PT Sinar Sosro Gunung Slamat. Seluruh data Anda terjaga kerahasiaannya.
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>