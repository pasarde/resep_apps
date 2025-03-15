<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->integer('recipe_id')->primary();
            $table->string('title');
            $table->string('image')->nullable();
            $table->integer('ready_in_minutes');
            $table->integer('servings');
            $table->text('instructions')->nullable();
            $table->text('ingredients')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};