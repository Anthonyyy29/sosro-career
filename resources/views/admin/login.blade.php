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
                    <input id="password" name="password" type="password" 
                        class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-sm transition-all focus:bg-white focus:ring-4 focus:ring-red-50 focus:border-red-500 outline-none" />
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