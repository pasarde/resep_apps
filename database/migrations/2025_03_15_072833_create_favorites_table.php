<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritesTable extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id(); // Auto-increment ID (opsional)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Kolom user_id, ga nullable
            $table->string('recipe_id'); // Kolom recipe_id
            $table->timestamps();

            // Composite unique key: user_id + recipe_id
            $table->unique(['user_id', 'recipe_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}