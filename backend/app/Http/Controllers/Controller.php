<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\Follower;



class Controller extends BaseController{


    public function __construct(){
        $this->middleware('auth:api');
    }


    public function getPosts(Request $request){
        $user = Auth::user();

        $followingIds = Follower::where('follower_id', $user->id)->pluck('following_id')->toArray();

        $posts = Post::whereIn('user_id', $followingIds)->get();

        $formattedPosts = [];
        foreach ($posts as $post) {
            $user = User::find($post->user_id);
            $formattedPosts[] = [
                'id' => $post->id,
                'image_url' => $post->image_url,
                'user_name' => $user->name,
                'likes' => $post->likes,
            ];
        }

        return response()->json([
            'status' => 'Success',
            'posts' => $formattedPosts,
        ]);
    }

    public function searchUsers(Request $request){
        $query = $request->name;
        $users = User::where('name', 'LIKE', "%$query%")->get();

        return response()->json([
            'status' => 'Success',
            'users' => $users,

        ]);
    }

    public function getPersonalPosts(Request $request){
        $user = Auth::user();
        $personalPosts = Post::where('user_id', $user->id)->get();

        return response()->json([
            'status' => 'Success',
            'posts' => $personalPosts,
        ]);
    }

    public function addFollower(Request $request){
        $user = Auth::user();
        $followingId = $request->following_id;

        $isFollowing = Follower::where('follower_id', $user->id)
            ->where('following_id', $followingId)
            ->exists();

        if ($isFollowing) {
            return response()->json([
                'status' => 'Error',
                'message' => 'You are already following this user.',
            ], 400);
        }

        if ($user->id === $followingId) {
            return response()->json([
                'status' => 'Error',
                'message' => 'You cannot follow yourself.',
            ], 400);
        }

        Follower::create([
            'follower_id' => $user->id,
            'following_id' => $followingId,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'You are now following the user.',
        ]);
    }

    public function toggleLike(Request $request){
        $user = Auth::user();
        $postId = $request->post_id;

        $hasLiked = Like::where('user_id', $user->id)->where('post_id', $postId)->exists();

        if ($hasLiked) {

            Like::where('user_id', $user->id)->where('post_id', $postId)->delete();
            $post = Post::find($postId);
            $post->decrement('likes');
            return response()->json([
                'status' => 'Success',
                'message' => 'Post unliked.',
            ]);
        }

        $like = new Like();
        $like->user_id = $user->id;
        $like->post_id = $postId;
        $like->save();

        $post = Post::find($postId);
        $post->increment('likes');

        return response()->json([
            'status' => 'Success',
            'message' => 'Post liked.',
        ]);
    }

    public function addPost(Request $request){
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'image_url' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $post = new Post();
        $post->user_id = $user->id;
        $post->image_url = $request->image_url;
        $post->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'Post added.',
        ]);
    }

    public function getPostLikes(Request $request){
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $postId = $request->post_id;

        $likedUserIds = Like::where('post_id', $postId)->pluck('user_id');
        $likedUsers = User::whereIn('id', $likedUserIds)->pluck('name');

        return response()->json([
            'status' => 'success',
            'liked_users' => $likedUsers,
        ]);
    }

    public function getUserTotalLikes() {
        $user = Auth::user();
        $userPosts = Post::where('user_id', $user->id)->get();
    
        $totalLikes = 0;
        foreach ($userPosts as $post) {
            $totalLikes += $post->likes;
        }
    
        return response()->json([
            'status' => 'Success',
            'total_likes' => $totalLikes,
            'user_name' => $user->name,
        ]);
    }
    
    public function getUserTotalFollowers() {
        $user = Auth::user();
        $totalFollowers = Follower::where('follower_id', $user->id)->count();
        return response()->json([
            'status' => 'Success',
            'total_followers' => $totalFollowers,
        ]);
    }

    public function unfollowUser(Request $request){
        $user = Auth::user();
        $followingId = $request->following_id;
    
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
            ]);
        } else {
            return response()->json([
                'status' => 'Error',
                'message' => 'You are not following this user.',
            ], 400);
        }
    }
    

    
}
