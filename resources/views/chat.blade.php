@extends('layouts.app')

@section('title', 'Chat')

@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <!-- Logo Chat -->
            <div class="mb-4">
                <svg class="w-16 h-16 mx-auto text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>

            <!-- Pesan Token -->
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Akses Forum Chat</h2>
            <p class="text-gray-600 mb-2">Copy token ini dan masukkan di halaman chat:</p>
            <p class="text-lg font-semibold text-blue-600 mb-6">{{ auth()->user()->createToken('auth_token')->plainTextToken }}</p>

            <!-- Tombol ke Forum Chat -->
            <a href="{{ route('chat') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-md">
                Buka Forum Chat
            </a>
        </div>
    </div>
@endsection