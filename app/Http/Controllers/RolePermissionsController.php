<?php
namespace App\Http\Controllers;

use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolePermissionsController extends Controller
{
    public function index()
    {
        $rolePermissions = RolePermission::with(['user', 'role'])->get();

        if ($rolePermissions->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
        }

        return response()->json($rolePermissions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission' => 'required|string|in:' . implode(',', config('permissions')),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rolePermission = RolePermission::create([
            'role_id' => $request->role_id,
            'permission' => $request->permission,
            'created_by' => $this->getCurrentUserId(),
        ]);

        return response()->json(['message' => 'Role Permission created successfully', 'rolePermission' => $rolePermission], 201);
    }

    public function show($id)
    {
        $rolePermission = RolePermission::with(['user', 'role'])->find($id);

        if (!$rolePermission) {
            return $this->getQueryIDNotFoundResponse('RolePermission', $id);
        }

        return response()->json($rolePermission);
    }

    public function update(Request $request, $id)
    {
        $rolePermission = RolePermission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission' => 'required|string|in:' . implode(',', config('permissions')),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rolePermission->update([
            'role_id' => $request->role_id,
            'permission' => $request->permission,
            'modified_by' => $this->getCurrentUserId(),
        ]);

        return response()->json(['message' => 'Role Permission updated successfully', 'rolePermission' => $rolePermission]);
    }

    public function destroy($id)
    {
        $rolePermission = RolePermission::find($id);

        if (!$rolePermission) {
            return $this->getQueryIDNotFoundResponse('RolePermission', $id);
        }

        $rolePermission->delete();

        return response()->json(['message' => 'Role Permission deleted successfully']);
    }
}