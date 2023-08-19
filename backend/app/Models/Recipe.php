<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'cuisine', 'ingredients', 'image_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function shoppingLists()
    {
        return $this->belongsToMany(ShoppingList::class, 'shopping_lists', 'recipe_id', 'shopping_list_id');
    }
}
