@extends('layouts.app')

@section('title', 'Chat Forum')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Chat Forum</h1>
        <iframe src="http://localhost:3000" style="width: 100%; height: 600px; border: none;"></iframe>
    </div>
@endsection