@extends('layouts.app')

@section('title', 'Favorites')

@section('content')
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-6">Your Favorite Recipes</h1>
    @if($favorites->isEmpty())
        <p class="text-center text-gray-500 text-lg">You haven't favorited any recipes yet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($favorites as $favorite)
                <div class="favorite-card p-4 bg-white rounded-lg shadow hover:shadow-lg transition duration-200">
                    <h3 class="text-xl font-bold text-gray-800">{{ $favorite->title }}</h3>
                    @if($favorite->image)
                        <img src="{{ $favorite->image }}" alt="{{ $favorite->title }}" class="w-full rounded mt-2">
                    @endif
                    <p class="mt-2 text-gray-600"><strong>Ready in:</strong> {{ $favorite->ready_in_minutes }} minutes</p>
                    <p class="text-gray-600"><strong>Servings:</strong> {{ $favorite->servings }}</p>
                    <div class="mt-3 flex gap-3">
                        <a href="{{ route('recipe.show', $favorite->recipe_id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-center transition duration-200">View Recipe</a>
                        <form method="POST" action="{{ route('recipe.favorite', $favorite->recipe_id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection