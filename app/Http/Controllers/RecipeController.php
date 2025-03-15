<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\Favorite;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RecipeController extends Controller
{
    protected Client $client;
    protected string $spoonacularKey;
    protected string $weatherKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->spoonacularKey = env('SPOONACULAR_KEY');
        $this->weatherKey = env('OPENWEATHER_KEY');
    }

    public function index(Request $request): View
    {
        $city = $request->input('city', 'Jakarta');
        $weatherData = $this->getWeatherRecommendations($city);
        return view('index', compact('weatherData'));
    }

    public function suggest(Request $request): JsonResponse
    {
        $ingredients = $request->query('ingredients');
        if (!$ingredients) {
            return response()->json(['error' => 'No ingredients provided'], 400);
        }

        $response = $this->client->get('https://api.spoonacular.com/recipes/complexSearch', [
            'query' => [
                'apiKey' => $this->spoonacularKey,
                'includeIngredients' => $ingredients,
                'number' => 5,
                'addRecipeInformation' => true,
            ]
        ]);

        $recipes = json_decode($response->getBody(), true)['results'];
        $likedIds = Like::pluck('recipe_id')->toArray();
        usort($recipes, fn($a, $b) => in_array($b['id'], $likedIds) <=> in_array($a['id'], $likedIds));

        return response()->json($recipes);
    }

    public function show(int $recipeId): View
    {
        $response = $this->client->get("https://api.spoonacular.com/recipes/{$recipeId}/information", [
            'query' => ['apiKey' => $this->spoonacularKey]
        ]);
        $recipe = json_decode($response->getBody(), true);
        return view('recipe', compact('recipe'));
    }

    public function favorite(Request $request, int $recipeId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to favorite a recipe.');
        }

        $response = $this->client->get("https://api.spoonacular.com/recipes/{$recipeId}/information", [
            'query' => ['apiKey' => $this->spoonacularKey]
        ]);
        $recipe = json_decode($response->getBody(), true);

        Favorite::updateOrCreate(
            ['recipe_id' => $recipeId, 'user_id' => Auth::id()],
            [
                'title' => $recipe['title'],
                'image' => $recipe['image'] ?? null,
                'ready_in_minutes' => $recipe['readyInMinutes'] ?? null,
                'servings' => $recipe['servings'] ?? null,
                'instructions' => $recipe['instructions'] ?? null,
                'ingredients' => implode(',', array_column($recipe['extendedIngredients'] ?? [], 'original'))
            ]
        );

        // Redirect berdasarkan halaman asal
        $route = $request->is('recipe/*') ? 'recipe.show' : 'index';
        $params = $request->is('recipe/*') ? $recipeId : [];
        return redirect()->route($route, $params)->with('success', 'Recipe added to favorites!');
    }

    public function like(Request $request, int $recipeId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to like a recipe.');
        }

        $response = $this->client->get("https://api.spoonacular.com/recipes/{$recipeId}/information", [
            'query' => ['apiKey' => $this->spoonacularKey]
        ]);
        $recipe = json_decode($response->getBody(), true);

        Like::updateOrCreate(
            ['recipe_id' => $recipeId, 'user_id' => Auth::id()],
            ['title' => $recipe['title']]
        );

        return redirect()->route('recipe.show', $recipeId)->with('success', 'You liked this recipe!');
    }

    public function unfavorite(Request $request, int $recipeId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to remove favorites.');
        }

        $favorite = Favorite::where('recipe_id', $recipeId)->where('user_id', Auth::id())->first();
        if ($favorite) {
            $favorite->delete();
            return redirect()->route('favorites')->with('success', 'Recipe removed from favorites!');
        }

        return redirect()->route('favorites')->with('error', 'Recipe not found in favorites.');
    }

    public function favorites(): View
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view favorites.');
        }

        $favorites = Favorite::where('user_id', Auth::id())->get();
        return view('favorites', compact('favorites'));
    }

    protected function getWeatherRecommendations(string $city): array
    {
        $geoUrl = 'http://api.openweathermap.org/geo/1.0/direct';
        $geoResponse = $this->client->get($geoUrl, [
            'query' => [
                'q' => $city,
                'limit' => 1,
                'appid' => $this->weatherKey
            ]
        ]);
        $geoData = json_decode($geoResponse->getBody(), true);
        $lat = $geoData[0]['lat'] ?? -6.2088;
        $lon = $geoData[0]['lon'] ?? 106.8456;

        $weatherResponse = $this->client->get('https://api.openweathermap.org/data/2.5/weather', [
            'query' => [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->weatherKey,
                'units' => 'metric'
            ]
        ]);
        $weatherData = json_decode($weatherResponse->getBody(), true);
        $temp = $weatherData['main']['temp'];
        $condition = strtolower($weatherData['weather'][0]['main']);
        $cityName = $weatherData['name'];

        $weatherRecipes = [
            'rain' => ['query' => 'soup', 'message' => "Hujan di {$cityName}! Ini rekomendasi makanan hangat:"],
            'clear' => ['query' => 'salad', 'message' => "Cerah di {$cityName}! Ini rekomendasi makanan segar:"],
            'clouds' => ['query' => 'pasta', 'message' => "Mendung di {$cityName}. Ini rekomendasi comfort food:"]
        ];
        $recommendation = $weatherRecipes[$condition] ?? ['query' => 'popular', 'message' => "Rekomendasi untuk {$cityName} hari ini:"];

        $recipeResponse = $this->client->get('https://api.spoonacular.com/recipes/complexSearch', [
            'query' => [
                'apiKey' => $this->spoonacularKey,
                'query' => $recommendation['query'],
                'number' => 20,
                'addRecipeInformation' => true,
                'instructionsRequired' => true,
                'fillIngredients' => true
            ]
        ]);
        $allRecipes = json_decode($recipeResponse->getBody(), true)['results'] ?? [];
        $recipes = array_slice($allRecipes, 0, 3);

        // Tambah pengecekan dan default value
        $recipes = array_map(function ($recipe) {
            return [
                'id' => $recipe['id'] ?? null,
                'title' => $recipe['title'] ?? 'No title',
                'image' => $recipe['image'] ?? '',
                'readyInMinutes' => $recipe['readyInMinutes'] ?? 0,
                'servings' => $recipe['servings'] ?? 0,
            ];
        }, $recipes);

        return [
            'weather' => ['temp' => round($temp), 'condition' => $condition, 'city' => $cityName],
            'message' => $recommendation['message'],
            'recipes' => $recipes
        ];
    }
}