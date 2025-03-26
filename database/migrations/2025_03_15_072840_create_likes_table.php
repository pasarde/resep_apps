<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id(); // Auto-increment ID (opsional)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Kolom user_id, ga nullable
            $table->string('recipe_id'); // Kolom recipe_id
            $table->string('title'); // Kolom title (dari method like)
            $table->timestamps();

            // Composite unique key (opsional, kalo mau satu user ga like resep yang sama dua kali)
            $table->unique(['user_id', 'recipe_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
}