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

        return response()->json($userRoles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_user_id' => 'nullable|exists:users,id',
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userRole = UserRole::create([
            'parent_user_id' => $request->parent_user_id,
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
            'created_by' => $this->getCurrentUserId(),
        ]);

        return response()->json(['message' => 'User Role created successfully', 'userRole' => $userRole], 201);
    }

    public function show($id)
    {
        try {
            $userRole = UserRole::with(['user', 'role'])->findOrFail($id);
            return response()->json($userRole);
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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userRole->update([
            'parent_user_id' => $request->parent_user_id,
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
            'modified_by' => $this->getCurrentUserId(),
        ]);

        return response()->json(['message' => 'User Role updated successfully', 'userRole' => $userRole]);
    }

    public function destroy($id)
    {
        try {
            $userRole = UserRole::findOrFail($id);
            $userRole->delete();

            return response()->json(['message' => 'User Role deleted successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getDeleteFailureResponse();
        }
    }
}