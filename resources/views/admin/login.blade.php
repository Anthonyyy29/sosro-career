{{-- Template Login Admin: --}}
<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center px-4 bg-gray-50/30">
        
        <div class="mb-3 px-4 py-1 bg-[#ffbf34] text-[10px] font-bold uppercase tracking-[0.2em] rounded-full shadow-sm">
            Administrator Portal
        </div>

        <div class="w-full sm:max-w-md bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-2xl p-8 md:p-10 border border-gray-100">
            
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="flex justify-center mb-6">
                    <img src="{{ asset('assets/images/SGS Logo-Color.webp') }}" 
                        alt="Logo Sinar Sosro Gunung Slamat" 
                        class="w-80 h-auto object-contain">
                </div>

                <div class="mb-5 group">
                    <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-700 ml-1 mb-1.5" />
                    <input id="email" name="email" type="email" 
                        class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-sm transition-all focus:bg-white focus:ring-4 focus:ring-red-50 focus:border-red-500 outline-none" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-5 group">
                    <x-input-label for="password" :value="__('Password')" class="font-semibold ml-1 mb-1.5" />
                    <div class="relative">
                        <input id="password" name="password" type="password"
                            class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-sm transition-all focus:bg-white focus:ring-4 focus:ring-red-50 focus:border-red-500 outline-none pr-12" />

                        <button type="button"
                            onclick="togglePassword('password', 'eyeOpen', 'eyeClose')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-red-600 transition-colors">

                            <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>

                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-500 transition-all">
                        <span class="ml-2 text-sm text-gray-500 group-hover:text-gray-700">Ingat Sesi Ini</span>
                    </label>

                    <a href="{{ route('admin.password.request') }}" class="text-sm font-semibold text-gray-400 hover:text-red-600 transition-colors">
                        Lupa Password?
                    </a>
                </div>

                <div class="mt-4 flex flex-col items-center justify-center">
                    <x-turnstile />

                    <x-input-error :messages="$errors->get('cf-turnstile-response')" class="text-xs" />
                </div>

                <div class="flex flex-col space-y-4 pt-4">
                    <button type="submit" class="w-full bg-[#ffbf34] hover:bg-[#eab02d] py-4 rounded-2xl font-bold transition-all shadow-xl shadow-gray-200 active:scale-95 flex items-center justify-center space-x-2">
                        <span>Login Admin</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-3 text-center pt-2 ">
                <a href="{{ route('login') }}" class="text-xs font-bold text-gray-400 hover:text-red-600 uppercase tracking-widest transition-colors">
                    &larr; Kembali ke Portal Pelamar
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>