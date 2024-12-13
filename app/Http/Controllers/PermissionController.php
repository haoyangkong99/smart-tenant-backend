<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class PermissionController extends Controller
{
    //Display a list of permissions
    public function index()
    {
        $permission_types=config('permissions');
        if (empty($permissionTypes)) {
            $this->getQueryAllNotFoundResponse();
         }

        return response()->json($permission_types);
    }
}
