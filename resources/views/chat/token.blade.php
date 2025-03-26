@extends('layouts.app')

@section('title', 'Generate Token')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Generate Token</h1>
        <p class="mb-4">Token Anda:</p>
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <code>{{ $token }}</code>
        </div>
        <a href="{{ route('chat.forum') }}" class="bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700">
            Buka Forum Chat
        </a>
    </div>
@endsection