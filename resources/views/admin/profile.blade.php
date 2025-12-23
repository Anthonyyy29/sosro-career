@extends('admin.layout')

@section('content')

<h1 class="text-2xl font-bold mb-6">User Profile</h1>

<div class="bg-white shadow rounded-xl p-6">

    {{-- PROFILE SECTION --}}
    <div class="flex items-center gap-6 pb-6 border-b">
        <img src="{{ asset('assets/images/profile1.png') }}" class="w-20 h-20 rounded-full border" alt="">

        <div>
            <h2 class="text-xl font-bold">{{ auth()->user()->name }}</h2>
            <p class="text-gray-600">Administrator</p>
            <p class="text-sm text-gray-500">Indonesia</p>
        </div>

        <div class="ml-auto flex gap-3">
            <button class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                Edit
            </button>
        </div>
    </div>


    {{-- PERSONAL INFO --}}
    <h3 class="text-lg font-semibold mt-6 mb-3">Personal Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-gray-50 p-4 rounded-lg border">
            <p class="text-sm text-gray-500">Full Name</p>
            <p class="font-medium">{{ auth()->user()->name }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border">
            <p class="text-sm text-gray-500">Email Address</p>
            <p class="font-medium">{{ auth()->user()->email }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border">
            <p class="text-sm text-gray-500">Phone</p>
            <p class="font-medium">-</p>
        </div>

    </div>

</div>

@endsection
