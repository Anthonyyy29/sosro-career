<x-app-layout>
    <div class="min-h-[calc(100vh-64px)] flex flex-col items-center justify-center px-4 py-8">
        
        <div class="w-full sm:max-w-md bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-2xl p-8 md:p-10 border border-gray-100">

            <div class="flex justify-center mb-6">
                <img src="{{ asset('assets/images/SGS Logo-Color.webp') }}" 
                    alt="Logo Sinar Sosro Gunung Slamat" 
                    class="w-80 h-auto object-contain">
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="email" value="Email" class="font-semibold text-gray-700 ml-1" />
                    <input id="email" type="email" name="email"
                        class="block mt-1 w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all outline-none"
                        :value="old('email')" required autofocus placeholder="Masukkan email Anda" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" value="Password" class="font-semibold text-gray-700 ml-1" />

                    <div class="relative">
                        <input id="password"
                            type="password"
                            name="password"
                            class="block mt-1 w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all outline-none pr-12"
                            required placeholder="••••••••" />

                        <button type="button"
                            onclick="togglePassword()"
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

                <div class="flex items-center justify-between pt-1">
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember"
                            class="rounded border-gray-300 text-red-600 focus:ring-red-500 shadow-sm transition-all">
                        <span class="ml-2 text-sm text-gray-500 group-hover:text-gray-700">Ingatkan saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm font-semibold text-gray-400 hover:text-red-600 transition-colors">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="mt-4 flex flex-col items-center justify-center">
                    <x-turnstile />

                    <x-input-error :messages="$errors->get('cf-turnstile-response')" class="text-xs" />
                </div>
                
                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white py-4 rounded-2xl font-bold transition-all shadow-lg shadow-red-200 active:scale-95">
                        Masuk
                    </button>
                </div>
            </form>

            <div class="flex flex-col items-center space-y-3 pt-4">
                @if (Route::has('register'))
                    <p class="text-sm text-gray-500">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-red-600 hover:underline font-bold ml-1">
                            Daftar di sini
                        </a>
                    </p>
                @endif

                {{-- <div class="h-px w-24 bg-gray-100"></div> --}}

                {{-- <a href="{{ route('admin.login') }}"
                    class="text-[12px] text-gray-400 hover:text-blue-600 font-semibold tracking-wider uppercase">
                    Portal Admin
                </a> --}}
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClose = document.getElementById('eyeClose');

            if (pass.type === "password") {
                pass.type = "text";
                eyeClose.classList.add("hidden");
                eyeOpen.classList.remove("hidden");
            } else {
                pass.type = "password";
                eyeOpen.classList.add("hidden");
                eyeClose.classList.remove("hidden");
            }
        }
    </script>
</x-app-layout>