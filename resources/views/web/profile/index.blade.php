@extends('layouts.form')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-md lg:max-w-2xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Profile</h1>
        </div>

        <!-- Profile Card -->
        <div class="bg-gradient-to-r from-teal-700 to-teal-600 rounded-3xl p-6 mb-6 shadow-lg">
            <div class="flex flex-col items-center">
                <!-- Profile Image -->
                <div class="w-24 h-24 rounded-full bg-white overflow-hidden mb-4 shadow-md">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->nama_petugas ?? 'User') . '&background=059669&color=fff' }}"
                         alt="Profile Picture"
                         class="w-full h-full object-cover">
                </div>

                <!-- Name -->
                <h2 class="text-xl font-bold text-white mb-1">{{ $user->nama_petugas ?? 'Rizky Aditya' }}</h2>

                <!-- Role/Status -->
                <p class="text-teal-100 text-sm">{{ $user->role ?? 'User Premium' }}</p>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <!-- Bumi Taman Digital Card -->
            <a href="{{ route('berkas-user') }}" class="bg-gradient-to-br from-lime-400 to-lime-500 rounded-2xl p-4 shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center space-x-3">
                <div class="bg-white bg-opacity-30 rounded-xl p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Surat tanah</h3>
                    <p class="text-gray-700 text-xs">Digital</p>
                </div>
            </a>

        <!-- Menu List -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <!-- Edit Profile -->
            @if(Auth::user()->role != "warga" )
            <a href="{{ route('profile.edit') }}" class="flex items-center px-5 py-4 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100">
                <div class="mr-4">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="text-gray-700 font-medium flex-1">Edit Profile</span>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            @endif
            <!-- Log Out -->
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="w-full flex items-center px-5 py-4 hover:bg-red-50 transition-colors duration-200 text-left">
                    <div class="mr-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <span class="text-red-600 font-medium flex-1">Log Out</span>
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
@endsection

