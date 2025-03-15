<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', [RecipeController::class, 'index'])->name('index');
Route::get('/recipe/{recipeId}', [RecipeController::class, 'show'])->name('recipe.show');
Route::get('/api/suggest', [RecipeController::class, 'suggest'])->name('recipe.suggest');

Route::middleware('auth')->group(function () {
    Route::post('/favorite/{recipeId}', [RecipeController::class, 'favorite'])->name('recipe.favorite');
    Route::delete('/favorite/{recipeId}', [RecipeController::class, 'unfavorite'])->name('recipe.unfavorite');
    Route::post('/like/{recipeId}', [RecipeController::class, 'like'])->name('recipe.like');
    Route::get('/favorites', [RecipeController::class, 'favorites'])->name('favorites');
    Route::post('/logout', [Auth::class, 'logout'])->name('logout');
});