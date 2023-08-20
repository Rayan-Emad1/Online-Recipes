<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;


Route::get("unauthorized", [AuthController::class, "unauthorized"])->name("unauthorized");
Route::post('/signin', [AuthController::class, 'signIn']);
Route::post('/signup', [AuthController::class, 'signUp']);


Route::group(["middleware" => "auth:api"], function () {
    
    Route::post('/search_recipe', [Controller::class, 'searchRecipes']);
    Route::get('/recipes', [Controller::class, 'getRecipes']);
    Route::post('/recipe_info', [Controller::class, 'getRecipeInfo']);
    
    Route::post('/like', [Controller::class, 'likeRecipe']);
    Route::post('/comment', [Controller::class, 'commentOnRecipe']);
    Route::post('/add_recipe', [Controller::class, 'addRecipeToList']);
    
    Route::get('/personal_info', [Controller::class, 'getPersonalInfo']);
    Route::post('/create_recipe', [Controller::class, 'createRecipe']);
    Route::post('/toggle_follow', [Controller::class, 'toggleFollowUser']);
    Route::post('/search_user', [Controller::class, 'searchUsers']);

});
