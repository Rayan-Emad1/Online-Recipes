<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;


Route::get("unauthorized", [AuthController::class, "unauthorized"])->name("unauthorized");
Route::post('/signin', [AuthController::class, 'signIn']);
Route::post('/signup', [AuthController::class, 'signUp']);


Route::group(["middleware" => "auth:api"], function () {
    Route::post('/search', [YourController::class, 'searchRecipes']);
    Route::get('/recipes', [YourController::class, 'getRecipes']);

    Route::get('/recipes/personal', [YourController::class, 'getPersonalRecipes']);
    Route::post('/add_recipe', [YourController::class, 'addRecipe']);
    Route::post('/like', [YourController::class, 'likeRecipe']);
    Route::post('/comment', [YourController::class, 'commentOnRecipe']);

    Route::get('/user/likes', [YourController::class, 'getUserTotalLikes']);
    Route::get('/user/followers', [YourController::class, 'getUserTotalFollowers']);
    
    Route::post('/recipe/like-toggle', [YourController::class, 'toggleLike']);
    Route::get('/recipe/likes', [YourController::class, 'getRecipeLikes']);

    Route::post('/add_list', [YourController::class, 'addRecipeToList']);
    Route::get('/get_list', [YourController::class, 'getListRecipes']);
});
