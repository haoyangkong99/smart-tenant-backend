<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{

    // Register a new user

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number'=>'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $admin = User::where('email','admin@gmail.com')->firstOrFail();
        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number'=>  $request->phone_number,
            'status'=>'ACTIVE',
            'created_by'=> $this->getCurrentUserId(),
        ]);
        $token = Auth::guard('api')->login($user);
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }
    public function registerwithouttoken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number'=>'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number'=>  $request->phone_number,
            'status'=>'ACTIVE',
        ]);
        $token = Auth::guard('api')->login($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    // Login the user
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('api')->user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);


    }

    // Logout the user
    public function logout(Request $request)
    {
        $user=Auth::user();
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
            'user' => Auth::user(),
        ]);
    }
/**
 * Refresh the user's token.
 */
/**
 * Refresh the user's token.
 */
public function refresh()
{
    // Ensure the user is authenticated
    if (!$user = Auth::guard('api')->user()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized or session expired. Please log in again.',
        ], 401);
    }

    // Re-generate token
    try {
        $newToken = Auth::guard('api')->login($user);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $newToken,
                'type' => 'bearer',
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unable to refresh token. Please try again.',
        ], 500);
    }
}


    // Get the authenticated user
    public function user()
    {
        try {
            $user = Auth::user();
            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve user information'], 500);
        }
    }
    public function show($id){

        try {
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('User',$id);
        }
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone_number' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'modified_by'=> $this->getCurrentUserId(),
        ]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
    public function changePassword (Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8'

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update([
            'password'=>Hash::make($request->password)
        ]);
        return response()->json(['message' => 'Password updated successfully', 'user' => $user]);


    }

}
