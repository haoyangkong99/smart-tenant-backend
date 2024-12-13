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
        ], 200);
    }
    public function getQueryAllNotFoundResponse(){
        return response()->json([
            'message' => 'No results found'
        ], 200);
    }
    public function getQueryIDNotFoundResponse($modelName,$id){
        return response()->json([
            'message' => "$modelName for ID: $id is not found."
        ], 200);
    }
}


?>