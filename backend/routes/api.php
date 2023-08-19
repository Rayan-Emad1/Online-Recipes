<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;


Route::get("unauthorized", [AuthController::class, "unauthorized"])->name("unauthorized");
Route::post('/signin', [AuthController::class, 'signIn']);
Route::post('/signup', [AuthController::class, 'signUp']);


Route::group(["middleware" => "auth:api"], function () {
    Route::post('/search', [Controller::class, 'searchRecipes']);
    Route::get('/recipes', [Controller::class, 'getRecipes']);
    
    Route::post('/add_recipe', [Controller::class, 'addRecipe']);
    Route::post('/like', [Controller::class, 'likeRecipe']);
    Route::post('/comment', [Controller::class, 'commentOnRecipe']);
    Route::get('/recipe/likes', [Controller::class, 'getRecipeLikes']);
    Route::post('/add_list', [Controller::class, 'addRecipeToList']);
    Route::get('/get_list', [Controller::class, 'getListRecipes']);
    
    Route::get('/follow', [Controller::class, 'FollowUser']);
    Route::get('/personal_info', [Controller::class, 'getPersonalInfo']);
    

});
