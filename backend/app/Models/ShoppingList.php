<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    protected $fillable = ['user_id', 'name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'lists', 'shopping_list_id', 'user_id');
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'lists', 'shopping_list_id', 'recipe_id');
    }
}
