<?php
namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        if ($roles->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
        }

        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:roles',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::create([
            'title' => $request->title,
            'created_by' => $this->getCurrentUserId(),
        ]);

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    public function show($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->getQueryIDNotFoundResponse('Role', $id);
        }

        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:roles,title,' . $id,

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role->update([
            'title' => $request->title,
            'modified_by' => $this->getCurrentUserId(),
        ]);

        return response()->json(['message' => 'Role updated successfully', 'role' => $role]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->getQueryIDNotFoundResponse('Role', $id);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}