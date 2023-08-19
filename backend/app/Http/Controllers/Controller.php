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


    function __construct(){
        $this->middleware('auth:api');
    }

    function getRecipes(Request $request){
        $user = Auth::user();
        $followingUserIds = $user->followings->pluck('id')->toArray();

        $recipes = Recipe::whereIn('user_id', $followingUserIds)->with('user', 'likes')->get();

        $formattedRecipes = [];
        foreach ($recipes as $recipe) {
            $isLikedByUser = $recipe->likes->contains('user_id', $user->id);
            $formattedRecipes[] = [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'cuisine' => $recipe->cuisine,
                'ingredients' => $recipe->ingredients,
                'image_url' => $recipe->image_url,
                'user_name' => $recipe->user->name,
                'likes' => $recipe->likes->count(),
                'is_liked_by_user' => $isLikedByUser,
            ];
        }

        return response()->json([
            'status' => 'Success',
            'recipes' => $formattedRecipes,
        ]);
    }

    function searchRecipes(Request $request){
        $category = $request->input('category', '');
        $query = $request->input('query', '');

        $queryBuilder = Recipe::query();

        if ($category) {
            $queryBuilder->where('cuisine', 'LIKE', "%$category%");
        }

        if ($query) {
            $queryBuilder->where('name', 'LIKE', "%$query%")
                        ->orWhere('ingredients', 'LIKE', "%$query%");
        }

        $recipes = $queryBuilder->with('user')->get();

        $formattedRecipes = [];
        foreach ($recipes as $recipe) {
            $formattedRecipe = [
                'owner' => $recipe->user->name,
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

    function getPersonalRecipes(Request $request){
        $user = Auth::user();
        $personalRecipes = $user->recipes;

        $formattedRecipes = [];
        foreach ($personalRecipes as $recipe) {
            $isLikedByUser = $recipe->likes->contains('user_id', $user->id);
            $formattedRecipe = [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'cuisine' => $recipe->cuisine,
                'ingredients' => $recipe->ingredients,
                'image_url' => $recipe->image_url,
                'likes' => $recipe->likes->count(), 
                'is_liked_by_user' => $isLikedByUser
            ];
            $formattedRecipes[] = $formattedRecipe;
        }

        return response()->json([
            'personal_recipes' => $formattedRecipes
        ]);
    }

    function addRecipe(Request $request){
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cuisine' => 'required|string|max:255',
            'ingredients' => 'required|string',
        ]);

        $recipe = new Recipe();
        $recipe->user_id = $user->id;
        $recipe->name = $data['name'];
        $recipe->cuisine = $data['cuisine'];
        $recipe->ingredients = $data['ingredients'];
        $recipe->image_url = $data['image_url'] ?? '';
        $recipe->save();

        return response()->json(['status' => 'Success', 'recipe' => $recipe]);
    }

    function likeRecipe(Request $request){
        $user = Auth::user();
        $recipeId = $request->input('recipe_id');

        $recipe = Recipe::find($recipeId);

        if (!$recipe) {
            return response()->json(['status' => 'Recipe not found'], 404);
        }

        $existingLike = Like::where('user_id', $user->id)
                            ->where('recipe_id', $recipe->id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['Message' => 'Unliked']);

        } else {
            $like = new Like;
            $like->user_id = $user->id;
            $like->recipe_id = $recipe->id;
            $like->save();
            return response()->json(['Message' => 'liked']);
        }

        
    }

    

    
}
