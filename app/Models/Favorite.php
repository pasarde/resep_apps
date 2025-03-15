<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorites';
    protected $primaryKey = 'recipe_id';
    public $incrementing = false;
    protected $fillable = [
        'recipe_id',
        'title',
        'image',
        'ready_in_minutes',
        'servings',
        'instructions',
        'ingredients'
    ];
}