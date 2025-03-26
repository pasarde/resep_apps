<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;


Route::get('/', [RecipeController::class, 'index'])->name('index');
Route::get('/home', [RecipeController::class, 'index'])->name('home');
Route::get('/recipe/{id}', [RecipeController::class, 'show'])->name('recipe.show');
Route::post('/recipe/favorite/{id}', [RecipeController::class, 'favorite'])->name('recipe.favorite');
Route::get('/favorites', [RecipeController::class, 'favorites'])->name('favorites');

Route::get('/chat/token', function () {
    $user = Auth::user() ?? App\Models\User::first();
    $token = $user->createToken('chat-token')->plainTextToken;
    return view('chat-token', ['token' => $token]);
});

Route::get('/chat/forum', function () {
    return view('chat.forum');
})->name('chat.forum');

Route::prefix('api')->group(function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'API route works TAPI DARI WEB.PHP AWOKAWOK']);
    });

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('auth:sanctum')->get('/chat', function (Request $request) {
        return [
            ['id' => 1, 'user' => $request->user()->name, 'message' => 'Halo, selamat datang di chat!'],
            ['id' => 2, 'user' => 'Bot', 'message' => 'Halo! Apa yang bisa dibantu?'],
        ];
    });

    Route::middleware('auth:sanctum')->post('/chat', function (Request $request) {
        $message = $request->input('message');
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        $chatMessage = [
            'id' => 3,
            'user' => $request->user()->name,
            'message' => $message,
        ];
        event(new \App\Events\MessageSent($chatMessage));
        return $chatMessage;
    });
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);