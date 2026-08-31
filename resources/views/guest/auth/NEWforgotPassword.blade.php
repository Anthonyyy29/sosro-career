@extends('guest.templates.index')

@section('content') 
<div class="flex flex-col items-center justify-center min-h-[50vh] px-4 py-8"> 
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6">Reset Password</h2>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5" onsubmit="return disableButton(this)"> 
            @csrf 

            <div>
                <x-input-label for="email" :value="__('Email')" /> 
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus /> 
                <x-input-error :messages="$errors->get('email')" class="mt-2" /> 
                <x-input-error :messages="$errors->get('password')" class="mt-2" /> 
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form> 
    </div> 
</div> 
@endsection
