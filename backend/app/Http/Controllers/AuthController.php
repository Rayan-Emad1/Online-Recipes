<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{  
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['signUp','signIn']]);
    }

    public function unauthorized(Request $request){
        return response()->json([
            'status' => 'Error',
            'message' => 'Unauthorized',
        ], 200);
    }

    public function signUp(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|string|min:1',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        // dd($request);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = Auth::login($user);
        // $user->token = $token;

        return response()->json([
            'message' => 'User created successfully',
            'token' => $token,
        ]);
    }

    public function signIn(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255',
            'password' => 'required|string|min:1',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        $credentials = $request->only('email', 'password');
    
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }
    
        $token = Auth::attempt($credentials);
    
        $user = Auth::user();
        $user->token = $token;
    
        return response()->json([
            'message' => 'User signin successfully',
            'token' => $token,
        ]);
    }

    public function logout(){
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
}
