@extends('layouts.app')

@section('title', $recipe['title'])

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-4xl font-bold text-gray-800 mb-6 text-center">{{ $recipe['title'] }}</h1>
        @if($recipe['image'])
            <div class="recipe-image mb-6">
                <img src="{{ $recipe['image'] }}" alt="{{ $recipe['title'] }}" class="w-full rounded-lg shadow-sm">
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-gray-600"><strong>Ready in:</strong> {{ $recipe['readyInMinutes'] ?? 'N/A' }} minutes</p>
                <p class="text-gray-600"><strong>Servings:</strong> {{ $recipe['servings'] ?? 'N/A' }}</p>
            </div>
        </div>
        <h2 class="text-2xl font-semibold text-gray-700 mt-4">Ingredients</h2>
        <ul class="list-disc pl-6 mt-2 text-gray-600">
            @foreach($recipe['extendedIngredients'] ?? [] as $ingredient)
                <li>{{ $ingredient['original'] }}</li>
            @endforeach
        </ul>
        <h2 class="text-2xl font-semibold text-gray-700 mt-4">Instructions</h2>
        <div class="prose text-gray-600 mt-2">{!! $recipe['instructions'] ?? 'No instructions available.' !!}</div>
        <div class="mt-6 flex gap-4 justify-center">
            <button data-modal-target="favoriteModal" data-modal-toggle="favoriteModal" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">Add to Favorites</button>
            <button data-modal-target="likeModal" data-modal-toggle="likeModal" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">Like</button>
        </div>
    </div>

    <!-- Modal for Favorite -->
    <div id="favoriteModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="favoriteModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-6 text-center">
                    <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Add this recipe to your favorites?</h3>
                    <form method="POST" action="{{ route('recipe.favorite', $recipe['id']) }}">
                        @csrf
                        <button type="submit" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">Yes, add it!</button>
                    </form>
                    <button data-modal-hide="favoriteModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900">No, cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Like -->
    <div id="likeModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="likeModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-6 text-center">
                    <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Like this recipe?</h3>
                    <form method="POST" action="{{ route('recipe.like', $recipe['id']) }}">
                        @csrf
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">Yes, like it!</button>
                    </form>
                    <button data-modal-hide="likeModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900">No, cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection