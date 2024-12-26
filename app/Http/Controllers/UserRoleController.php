<?php
namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserRoleController extends Controller
{
    public function index()
    {
        $userRoles = UserRole::with(['user', 'role'])->get();

        if ($userRoles->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
        }

        return $this->successfulQueryResponse($userRoles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_user_id' => 'nullable|exists:users,id',
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $userRole = UserRole::create([
            'parent_user_id' => $request->parent_user_id,
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
            'created_by' => $this->getCurrentUserId(),
        ]);

        return $this->successfulCreationResponse($userRole) ;
    }

    public function show($id)
    {
        try {
            $userRole = UserRole::with(['user', 'role'])->findOrFail($id);
            return $this->successfulQueryResponse($userRole);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('UserRole', $id);
        }
    }

    public function update(Request $request, $id)
    {
        $userRole = UserRole::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'parent_user_id' => 'nullable|exists:users,id',
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $userRole->update([
            'parent_user_id' => $request->parent_user_id,
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
            'modified_by' => $this->getCurrentUserId(),
        ]);

        return $this->successfulUpdateResponse($userRole);
    }

    public function destroy($id)
    {
        try {
            $userRole = UserRole::findOrFail($id);
            $userRole->delete();

            return $this->successfulDeleteResponse($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getDeleteFailureResponse();
        }
    }
}