<x-app-layout>
    <div class="min-h-[calc(100vh-64px)] flex flex-col items-center justify-center px-4 py-12">

        <div class="w-full sm:max-w-md bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2rem] border border-gray-100 p-8 md:p-10">

            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Reset Password</h2>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="group">
                    <label for="email" class="text-[13px] font-semibold text-gray-600 ml-1 mb-1.5 block group-focus-within:text-red-600 transition-colors">
                        {{ __('Alamat Email') }}
                    </label>
                    <input id="email"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm transition-all focus:bg-white focus:ring-4 focus:ring-red-50 focus:border-red-500 outline-none"
                           type="email"
                           name="email"
                           value="{{ old('email', $request->email) }}"
                           required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="group">
                    <label for="password" class="text-[13px] font-semibold text-gray-600 ml-1 mb-1.5 block group-focus-within:text-red-600 transition-colors">
                        {{ __('Password Baru') }}
                    </label>
                    <input id="password"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm transition-all focus:bg-white focus:ring-4 focus:ring-red-50 focus:border-red-500 outline-none"
                           type="password"
                           name="password"
                           required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="group">
                    <label for="password_confirmation" class="text-[13px] font-semibold text-gray-600 ml-1 mb-1.5 block group-focus-within:text-red-600 transition-colors">
                        {{ __('Konfirmasi Password Baru') }}
                    </label>
                    <input id="password_confirmation"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm transition-all focus:bg-white focus:ring-4 focus:ring-red-50 focus:border-red-500 outline-none"
                           type="password"
                           name="password_confirmation"
                           required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full group relative flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-300 bg-red-600 rounded-2xl hover:bg-red-700 hover:shadow-xl hover:shadow-red-200 active:scale-95">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-sm font-bold text-gray-400 hover:text-red-600 transition-colors">
                    &larr; Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
