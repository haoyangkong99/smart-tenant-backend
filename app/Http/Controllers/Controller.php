<?php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
class Controller extends BaseController
{
    public function getCurrentUserId()
    {
        $user = Auth::user();
        $userId=$user->id;
        return $userId;
    }
    public function getCurrentDate()
    {
        $currentDate = now();
        $formattedDate = $currentDate->format('Y-m-d');
        return $formattedDate;
    }
    public function getDeleteFailureResponse ()
    {
        return response()->json([
            'message' => 'Delete request failure.',
            'data'=> []
        ], 400);
    }
    public function getQueryAllNotFoundResponse(){
        return response()->json([
            'message' => 'No results found',
            'data'=>[]
        ], 404);
    }
    public function getQueryIDNotFoundResponse($modelName,$id){
        return response()->json([
            'message' => "$modelName for ID: $id is not found.",
            'data'=>[]
        ], 404);
    }
    public function getQueryInvalidResponse(){
        return response()->json([
            'message' => "Invalid Request",
            'data'=>[]
        ], 400);
    }
    public function getGeneralExceptionResponse($e)
    {
        return response()->json([
            'message' => 'Error occured.',
            'error' => $e], 500);
    }
    public function validationErrorResponse ($error)
    {
        return response()->json([
            'message' => 'Error occured.',
            'error' => $error], 422);
    }
    public function successfulCreationResponse($content)
    {
        return response()->json(['message' => 'Record is created successfully.', 'data' => $content], 201);
    }
    public function successfulUpdateResponse ($content)
    {
        return response()->json(['message' => 'Record is updated successfully.', 'data' => $content], 200);
    }
    public function successfulUpdatePasswordResponse ($content)
    {
        return response()->json(['message' => 'Password is updated successfully.', 'data' => $content], 200);
    }
    public function successfulQueryResponse ($content)
    {
        return response()->json(['message' => 'Result is found', 'data' => $content], 200);
    }
    public function successfulDeleteResponse($id)
    {
        return response()->json([
            'message' => 'Record for ID:'.$id.'is deleted successfully',
            'data'=>[]
        ], 200);
    }
    public function successfulRegistrationResponse($user,$token)
    {
        return response()->json([
            'message' => 'User registered successfully',
            'data' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }
    public function successfulLoginResponse ($user,$token)
    {
        return response()->json([
            'message' => 'Login successful',
            'data' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
    public function successfulRefreshTokenResponse ($user,$token)
    {
        return response()->json([
            'message' => 'Token refresh successful',
            'data' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
    public function successfulLogoutResponse ($user)
    {
        return response()->json([
            'message' => 'Successfully logged out',
            'data' => $user,
        ]);
    }
    public function sessionExpiredResponse ()
    {
        return response()->json([
            'message' => 'Unauthorized or session expired. Please log in again.',
            'data'=>[]
        ], 401);
    }
}


?>