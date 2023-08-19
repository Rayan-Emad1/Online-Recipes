<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;


Route::get("unauthorized", [AuthController::class, "unauthorized"])->name("unauthorized");
Route::post('/signin', [AuthController::class, 'signIn']);
Route::post('/signup', [AuthController::class, 'signUp']);


Route::group(["middleware" => "auth:api"], function(){
    Route::post('/users/search', [Controller::class, 'searchUsers']);
    Route::get('/posts', [Controller::class, 'getPosts']);

    Route::get('/posts/personal', [Controller::class, 'getPersonalPosts']);
    Route::post('/addpost', [Controller::class, 'addPost']);
    Route::post('/follow', [Controller::class, 'addFollower']);
    Route::post('/unfollow', [Controller::class, 'unfollowUser']);

    Route::get('/user/likes', [Controller::class, 'getUserTotalLikes']);
    Route::get('/user/followers', [Controller::class, 'getUserTotalFollowers']);
    
    Route::post('/like', [Controller::class, 'toggleLike']);
    Route::post('/post/likes', [Controller::class, 'getPostLikes']); // wanted to get names of whom liked the photos

});
