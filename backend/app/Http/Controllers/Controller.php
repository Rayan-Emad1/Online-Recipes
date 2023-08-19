<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Models\List;



class Controller extends BaseController{


    public function __construct(){
        $this->middleware('auth:api');
    }


    public function getRecipes(Request $request){

        $user = Auth::user();
        $followingIds = Follower::where('follower_id', $user->id)->pluck('following_id')->toArray();
        $recipes = Recipe::whereIn('user_id', $followingIds)->get();

        $formattedRecipes = [];
        foreach ($recipes as $recipe) {
            $user = $recipe->user;
            $formattedRecipes[] = [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'cuisine' => $recipe->cuisine,
                'ingredients' => $recipe->ingredients,
                'image_url' => $recipe->image_url,
                'user_name' => $user->name,
                'likes' => $recipe->likes->count(),
            ];
        }

        return response()->json([
            'status' => 'Success',
            'recipes' => $formattedRecipes,
        ]);
    }

    public function searchRecipes(Request $request){
        $cuisine = $request->cuisine ?? '';
        $query = $request->query ?? '';

        $queryBuilder = Recipe::query();

        if ($cuisine) {
            $queryBuilder->where('cuisine', 'LIKE', "%$cuisine%");
        }

        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                ->orWhere('ingredients', 'LIKE', "%$query%");
            });
        }

        $recipes = $queryBuilder->get();

        $formattedRecipes = [];
        foreach ($recipes as $recipe) {
            $formattedRecipe = [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'cuisine' => $recipe->cuisine,
                'ingredients' => $recipe->ingredients,
                'image_url' => $recipe->image_url,
            ];
            $formattedRecipes[] = $formattedRecipe;
        }

        return response()->json(['recipes' => $formattedRecipes]);
    }

    public function getPersonalRecipes(Request $request){
        $user = Auth::user();
        $personalRecipes = $user->recipes;

        $formattedRecipes = [];
        foreach ($personalRecipes as $recipe) {
            $formattedRecipe = [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'cuisine' => $recipe->cuisine,
                'ingredients' => $recipe->ingredients,
                'image_url' => $recipe->image_url,
                'likes' => $recipe->likes->count(), 
            ];
            $formattedRecipes[] = $formattedRecipe;
        }

        return response()->json(['personal_recipes' => $formattedRecipes]);
    }

    
}
