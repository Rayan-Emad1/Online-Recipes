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
use App\Models\ShoppingList;
use App\Models\Follower;


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
            $isInList = ShoppingList::where('user_id', $user->id)->where('recipe_id', $recipe->id)->exists();

            $formattedRecipes[] = [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'cuisine' => $recipe->cuisine,
                'ingredients' => $recipe->ingredients,
                'image_url' => $recipe->image_url,
                'user_name' => $recipe->user->name,
                'likes' => $recipe->likes->count(),
                'is_liked_by_user' => $isLikedByUser,
                'is_in_list' => $isInList,
            ];
        }

        return response()->json([
            'status' => 'Success',
            'recipes' => $formattedRecipes,
        ]);
    }

    function searchRecipes(Request $request){
        $user = Auth::user();
        $query = $request->input('query', '');

        $queryBuilder = Recipe::query();

        if ($query) {
            $queryBuilder->where('name', 'LIKE', "%$query%")
                        ->orwhere('cuisine', 'LIKE', "%$query%")
                        ->orWhere('ingredients', 'LIKE', "%$query%");
        }

        $recipes = $queryBuilder->with('user')->get();

        $formattedRecipes = [];
        foreach ($recipes as $recipe) {
            $isLikedByUser = $recipe->likes->contains('user_id', $user->id);
            $isInList = ShoppingList::where('user_id', $user->id)->where('recipe_id', $recipe->id)->exists();
            $formattedRecipe = [
                'owner' => $recipe->user->name,
                'id' => $recipe->id,
                'name' => $recipe->name,
                'cuisine' => $recipe->cuisine,
                'ingredients' => $recipe->ingredients,
                'image_url' => $recipe->image_url, 
                'is_liked_by_user' => $isLikedByUser,
                'is_in_list' => $isInList,
            ];
            $formattedRecipes[] = $formattedRecipe;
        }
        return response()->json(['recipes' => $formattedRecipes]);
    }
    
    function getRecipeInfo(Request $request){
        $recipeId = $request->input('recipe_id');
        $recipe = Recipe::find($recipeId);

        if (!$recipe) {
            return response()->json(['status' => 'Recipe not found'], 404);
        }

        $likesByUsers = [];
        foreach ($recipe->likes as $like) {
            $likesByUsers[] = [
                'user_name' => $like->user->name,
            ];
        }

        $comments = [];
        foreach ($recipe->comments as $comment) {
            $comments[] = [
                'user_name' => $comment->user->name,
                'comment_text' => $comment->comment_text,
            ];
        }

        $recipeInfo = [
            'id' => $recipe->id,
            'name' => $recipe->name,
            'cuisine' => $recipe->cuisine,
            'ingredients' => $recipe->ingredients,
            'image_url' => $recipe->image_url,
            'user_name' => $recipe->user->name,
            'likes' => $recipe->likes->count(),
            'is_liked_by_user' => $recipe->likes->contains('user_id', Auth::id()),
            'likes_by_users' => $likesByUsers,
            'comments' => $comments,
        ];

        return response()->json([
            'status' => 'Success',
            'recipe_info' => $recipeInfo,
        ]);
    }

    function togglelikeRecipe(Request $request){
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

    function commentOnRecipe(Request $request){
        $user = Auth::user();
        $recipeId = $request->recipe_id;
        $commentText = $request->comment_text;

        $recipe = Recipe::find($recipeId);

        if (! ($recipe && $commentText) ) {
            return response()->json(['status' => 'Recipe not found'], 404);
        }

        $comment = new Comment([
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'comment_text' => $commentText,
        ]);

        $comment->save();

        return response()->json(['Message' => 'Comment Added']);
    }

    function toggleRecipeInList(Request $request){
        $user = Auth::user();
        $recipeId = $request->input('recipe_id');
        $recipe = Recipe::find($recipeId);
    
        $list = ShoppingList::where('user_id', $user->id)->where('recipe_id', $recipeId)->first();
    
        if ($list) {
            $list->delete();
            return response()->json(['status' => 'Recipe removed from shopping list']);
        } else {
            $newList = new ShoppingList;
            $newList->user_id = $user->id;
            $newList->recipe_id = $recipe->id;
            $newList->save();
    
            return response()->json([
                'status' => 'Recipe added to shopping list',
                'list' => $newList
            ]);
        }
    }

    function getPersonalInfo(Request $request){
        $user = Auth::user();
        $ownRecipes = $user->recipes;
    
        $formattedOwnRecipes = $ownRecipes->map(function ($recipe) use ($user) {
            $isLikedByUser = $recipe->likes->contains('user_id', $user->id);
            $isInList = ShoppingList::where('user_id', $user->id)->where('recipe_id', $recipe->id)->exists();
    
            return [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'cuisine' => $recipe->cuisine,
                'ingredients' => $recipe->ingredients,
                'image_url' => $recipe->image_url,
                'user_name' => $user->name,
                'likes' => $recipe->likes->count(),
                'is_liked_by_user' => $isLikedByUser,
                'is_in_list' => $isInList,
            ];
        });

        $shoppingLists = $user->shoppingLists->map(function ($list) {
            return $list->getRecipeDetails();
        });
        $totalLikes = $user->likes()->count();
        $totalFollowers = $user->followers()->count();
    
        return response()->json([
            'user_name' => $user->name,
            'total_likes' => $totalLikes,
            'total_followers' => $totalFollowers,
            'personal_recipes' => $formattedOwnRecipes,
            'list_recipes' => $shoppingLists
        ]);
    }

    function createRecipe(Request $request){
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cuisine' => 'required|string|max:255',
            'ingredients' => 'required|string',
        ]);
        
        base64_decode($request->image);
        $image = $request->image;
        $image_name = time().'.'.$request->image->extension();  
        $image->move(public_path('/images'), $image_name);
        $image_url = asset('images/' . $image_name);

        $recipe = new Recipe();
        $recipe->user_id = $user->id;
        $recipe->name = $data['name'];
        $recipe->cuisine = $data['cuisine'];
        $recipe->ingredients = $data['ingredients'];
        $recipe->image_url = $image_url ?? "" ;
        $recipe->save();

        return response()->json(['status' => 'Success', 'recipe' => $recipe]);
    }

    function toggleFollowUser(Request $request){
        $user = Auth::user();
        $followingId = $request->input('following_id');
    
        $isFollowing = Follower::where('follower_id', $user->id)
            ->where('following_id', $followingId)
            ->exists();
    
        if ($isFollowing) {
            Follower::where('follower_id', $user->id)
                ->where('following_id', $followingId)
                ->delete();
    
            return response()->json([
                'status' => 'Success',
                'message' => 'You have unfollowed the user.',
                'is_following' => false,
            ]);
        } else {
            Follower::create([
                'follower_id' => $user->id,
                'following_id' => $followingId,
            ]);
    
            return response()->json([
                'status' => 'Success',
                'message' => 'You are now following the user.',
                'is_following' => true,
            ]);
        }
    }

    function searchUsers(Request $request){
        $query = $request->input('query');
    
        $users = User::where('name', 'LIKE', "%$query%")->get();
    
        $formattedUsers = [];
        foreach ($users as $user) {
            $isFollowing = Follower::where('follower_id', Auth::id())
                ->where('following_id', $user->id)
                ->exists();
    
            $formattedUser = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_following' => $isFollowing,
            ];
    
            $formattedUsers[] = $formattedUser;
        }
    
        return response()->json([
            'status' => 'Success',
            'users' => $formattedUsers,
        ]);
    }
    
}
