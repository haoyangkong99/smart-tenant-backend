<?php
namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('user')->get();

        if ($tenants->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
        }

        return response()->json($tenants);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'total_family_member' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tenant = Tenant::create([
            'user_id' => $request->user_id,
            'total_family_member' => $request->total_family_member,
            'created_by' => $this->getCurrentUserId(),
        ]);

        return response()->json(['message' => 'Tenant created successfully', 'tenant' => $tenant], 201);
    }

    public function show($id)
    {
        try {
            $tenant = Tenant::with('user')->findOrFail($id);
            return response()->json($tenant);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('Tenant', $id);
        }
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'total_family_member' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tenant->update([
            'user_id' => $request->user_id,
            'total_family_member' => $request->total_family_member,
            'modified_by' => $this->getCurrentUserId(),
        ]);

        return response()->json(['message' => 'Tenant updated successfully', 'tenant' => $tenant]);
    }

    public function destroy($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            $tenant->delete();

            return response()->json(['message' => 'Tenant deleted successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getDeleteFailureResponse();
        }
    }
}