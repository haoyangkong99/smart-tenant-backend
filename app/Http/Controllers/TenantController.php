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

        return $this->successfulQueryResponse($tenants);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'total_family_member' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $tenant = Tenant::create([
            'user_id' => $request->user_id,
            'total_family_member' => $request->total_family_member,
            'created_by' => $this->getCurrentUserId(),
        ]);

        return $this->successfulCreationResponse($tenant) ;
    }

    public function show($id)
    {
        try {
            $tenant = Tenant::with('user')->findOrFail($id);
            return $this->successfulQueryResponse($tenant);
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
            return $this->validationErrorResponse($validator->errors());
        }

        $tenant->update([
            'user_id' => $request->user_id,
            'total_family_member' => $request->total_family_member,
            'modified_by' => $this->getCurrentUserId(),
        ]);

        return $this->successfulUpdateResponse($tenant);
    }

    public function destroy($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            $tenant->delete();

            return $this->successfulDeleteResponse($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getDeleteFailureResponse();
        }
    }
}