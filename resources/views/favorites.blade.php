@extends('layouts.app')

@section('title', 'Favorite Recipes')

@section('content')
    <div class="container">
        <h1>Your Favorite Recipes</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (empty($recipes))
            <p>No favorite recipes found.</p>
        @else
            <div class="row">
                @foreach ($recipes as $recipe)
                    <div class="col-md-4">
                        <div class="card mb-4">
                            @if ($recipe['image'])
                                <img src="{{ $recipe['image'] }}" class="card-img-top" alt="{{ $recipe['title'] }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('recipe.show', $recipe['id']) }}" class="text-blue-500 hover:underline">
                                        {{ $recipe['title'] }}
                                    </a>
                                </h5>
                                <p class="card-text">
                                    Ready in: {{ $recipe['readyInMinutes'] }} minutes<br>
                                    Servings: {{ $recipe['servings'] }}
                                </p>
                                <form action="{{ route('recipe.unfavorite', $recipe['id']) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded">Remove from Favorites</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection