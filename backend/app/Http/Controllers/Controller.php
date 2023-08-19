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
            $user = User::find($recipe->user_id);
            $formattedRecipes[] = [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'cuisine' => $recipe->cuisine,
                'ingredients' => $recipe->ingredients,
                'image_url' => $recipe->image_url,
                'user_name' => $user->name,
                'likes' => $recipe->likes()->count(),
            ];
        }
    
        return response()->json([
            'status' => 'Success',
            'recipes' => $formattedRecipes,
        ]);
    }

    
}
