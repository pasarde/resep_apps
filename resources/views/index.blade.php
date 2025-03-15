@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="mb-6">
        <h1 class="text-4xl font-bold text-center mb-6 text-gray-800">Discover Your Next Meal</h1>
        <form id="ingredientForm" action="{{ route('index') }}" method="GET" class="flex gap-4 items-center justify-center">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                </div>
                <input type="text" name="city" placeholder="Enter city (e.g., Jakarta)" value="{{ request('city', 'Jakarta') }}" class="w-full pl-10 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-md">Search</button>
        </form>
    </div>

    @if($weatherData)
        <div class="mb-6 p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-700">{{ $weatherData['weather']['city'] }}: {{ $weatherData['weather']['temp'] }}°C, {{ $weatherData['weather']['condition'] }}</h2>
            <p class="mt-2 text-gray-600 italic">{{ $weatherData['message'] }}</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                @foreach($weatherData['recipes'] as $recipe)
                    <div class="recipe-card p-4 bg-gray-50 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-xl font-bold text-gray-800">{{ $recipe['title'] }}</h3>
                        @if($recipe['image'])
                            <img src="{{ $recipe['image'] }}" alt="{{ $recipe['title'] }}" class="w-full rounded mt-2">
                        @endif
                        <p class="mt-2 text-gray-600"><strong>Ready in:</strong> {{ $recipe['readyInMinutes'] ?? 'N/A' }} minutes</p>
                        <p class="text-gray-600"><strong>Servings:</strong> {{ $recipe['servings'] ?? 'N/A' }}</p>
                        <div class="mt-3 flex gap-3">
                            <a href="{{ route('recipe.show', $recipe['id']) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-center transition duration-200">View Recipe</a>
                            <form method="POST" action="{{ route('recipe.favorite', $recipe['id']) }}">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">Favorite</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-4">Suggest Recipe by Ingredients</h2>
        <form id="suggestForm" action="/api/suggest" method="GET" class="flex gap-4 items-center justify-center">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                </div>
                <input type="text" id="ingredients" name="ingredients" placeholder="Enter ingredients (e.g., chicken, rice)" class="w-full pl-10 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm">
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-md">Suggest</button>
        </form>
        <div id="results" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6"></div>
    </div>

    <script>
        document.getElementById("suggestForm").addEventListener("submit", function(e) {
            e.preventDefault();
            var ingredients = document.getElementById("ingredients").value;
            fetch("/api/suggest?ingredients=" + encodeURIComponent(ingredients))
                .then(response => response.json())
                .then(data => {
                    var resultsDiv = document.getElementById("results");
                    resultsDiv.innerHTML = "";
                    if (data.error) {
                        resultsDiv.innerHTML = "<p class='text-red-500 text-center'>Error: " + data.error + "</p>";
                    } else if (data.length === 0) {
                        resultsDiv.innerHTML = "<p class='text-gray-500 text-center'>No recipes found.</p>";
                    } else {
                        data.forEach(function(recipe) {
                            var recipeDiv = document.createElement("div");
                            recipeDiv.className = "p-4 bg-white rounded-lg shadow hover:shadow-lg transition duration-200";
                            recipeDiv.innerHTML = `
                                <h2 class="text-xl font-bold text-gray-800">${recipe.title}</h2>
                                <p class="text-gray-600"><strong>Ready in:</strong> ${recipe.readyInMinutes || "N/A"}</p>
                                <p class="text-gray-600"><strong>Servings:</strong> ${recipe.servings || "N/A"}</p>
                                <div class="mt-3 flex gap-3">
                                    <a href="/recipe/${recipe.id}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">View Recipe</a>
                                    <form method="POST" action="/recipe/favorite/${recipe.id}">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">Favorite</button>
                                    </form>
                                </div>
                                ${recipe.image ? `<img src="${recipe.image}" alt="${recipe.title}" class="w-full rounded mt-2">` : ''}
                                <p class="mt-2 text-gray-600">${recipe.summary || ''}</p>
                            `;
                            resultsDiv.appendChild(recipeDiv);
                        });
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    </script>
@endsection