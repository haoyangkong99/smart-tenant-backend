<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class AuthController extends Controller
{

    // Register a new user

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone_number'=>'required|string',
            ]);
            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
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
            return $this->successfulRegistrationResponse($user,$token);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getQueryAllNotFoundResponse();
        }
        catch (Exception $e){
            return $this->getGeneralExceptionResponse($e);
        }


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
            return $this->validationErrorResponse($validator->errors());
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

        return $this->successfulRegistrationResponse($user,$token);
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
        return $this->successfulLoginResponse($user,$token);

    }

    // Logout the user
    public function logout(Request $request)
    {
        $user=Auth::user();
        Auth::guard('api')->logout();
        return $this->successfulLogoutResponse($user);
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
        return $this->sessionExpiredResponse();
    }

    // Re-generate token
    try {
        $newToken = Auth::guard('api')->login($user);

        return $this->successfulRefreshTokenResponse($user,$newToken);
    } catch (\Exception $e) {
        return $this->getGeneralExceptionResponse($e);
    }
}


    // Get the authenticated user
    public function user()
    {
        try {
            $user = Auth::user();
            return $this->successfulQueryResponse($user);
        } catch (\Exception $e) {
            return $this->getGeneralExceptionResponse($e);
        }
    }
    public function userAll()
    {
        try {
            $user = User::all();
            return $this->successfulQueryResponse($user);
        } catch (\Exception $e) {
            return $this->getGeneralExceptionResponse($e);
        }
    }
    public function show($id){

        try {
            $user = User::where('id',$id)->firstOrFail();
            return $this->successfulQueryResponse($user);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('User',$id);
        }
    }
    public function update(Request $request, $id)
    {
        $user = User::where('id',$id)->firstOrFail();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone_number' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'modified_by'=> $this->getCurrentUserId(),
        ]);
        return $this->successfulUpdateResponse($user);
    }
    public function changePassword (Request $request, $id)
    {
        $user = User::where('id',$id)->firstOrFail();
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8'

        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user->update([
            'password'=>Hash::make($request->password)
        ]);
        return $this->successfulUpdatePasswordResponse($user);


    }

}
