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

        return $this->successfulQueryResponse($rolePermissions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission' => 'required|string|in:' . implode(',', config('permissions')),
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $rolePermission = RolePermission::create([
            'role_id' => $request->role_id,
            'permission' => $request->permission,
            'created_by' => $this->getCurrentUserId(),
        ]);

        return $this->successfulCreationResponse($rolePermission) ;
    }

    public function show($id)
    {

        try {
            $rolePermission = RolePermission::findOrFail($id);
            return $this->successfulQueryResponse($rolePermission);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('RolePermission',$id);
        }
    }

    public function update(Request $request, $id)
    {
        $rolePermission = RolePermission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission' => 'required|string|in:' . implode(',', config('permissions')),
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $rolePermission->update([
            'role_id' => $request->role_id,
            'permission' => $request->permission,
            'modified_by' => $this->getCurrentUserId(),
        ]);

        return $this->successfulUpdateResponse($rolePermission);
    }

    public function destroy($id)
    {

        try {
            $rolePermission = RolePermission::find($id);
            $rolePermission->delete();

            return $this->successfulDeleteResponse($id);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };

    }
}