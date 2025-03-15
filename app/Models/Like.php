<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';
    protected $primaryKey = 'recipe_id';
    public $incrementing = false;
    protected $fillable = ['recipe_id', 'title', 'likes'];
}