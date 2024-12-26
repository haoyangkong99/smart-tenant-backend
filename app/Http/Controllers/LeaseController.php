<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaseController extends Controller
{
    public function index()
    {
        $leases = Lease::with(['property', 'unit', 'tenant'])->get();
        if ($leases->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
         }
         return $this->successfulQueryResponse($leases);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:property,id',
            'unit_id' => 'required|exists:property_units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'lease_number' => 'required|string|unique:leases,lease_number',
            'rent_start_date' => 'required|date',
            'rent_end_date' => 'required|date|after:rent_start_date',
            'rent_amount' => 'required|numeric',
            'rent_type' => 'required|string',
            'terms' => 'required|integer',
            'deposit_amount' => 'required|numeric',
            'deposit_description' => 'required|string',
            'contract' => 'nullable|file',
            'status' => 'required|string',
             // Ensure created_by is an integer
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('contract')) {
            $validatedData['contract'] = $request->file('contract')->store('contracts');
        }

        $lease = Lease::create($validatedData);
        return $this->successfulCreationResponse($lease);
    }

    public function show($id)
    {
        try {
            $lease = Lease::with(['property', 'unit', 'tenant'])->findOrFail($id);
            return $this->successfulQueryResponse($lease);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('Lease',$id);
        }

    }

    public function update(Request $request, $id)
    {
        $lease = Lease::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:property,id',
            'unit_id' => 'required|exists:property_units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'lease_number' => 'required|string|unique:leases,lease_number,' . $id,
            'rent_start_date' => 'required|date',
            'rent_end_date' => 'required|date|after:rent_start_date',
            'rent_amount' => 'required|numeric',
            'rent_type' => 'required|string',
            'terms' => 'required|integer',
            'deposit_amount' => 'required|numeric',
            'deposit_description' => 'required|string',
            'contract' => 'nullable|file',
            'status' => 'required|string',

        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('contract')) {
            $validatedData['contract'] = $request->file('contract')->store('contracts');
        }

        $lease->update($validatedData);
        return $this->successfulUpdateResponse($lease);
    }

    public function destroy($id)
    {
        try {
            $lease = Lease::findOrFail($id);
            $lease->delete();

            return $this->successfulDeleteResponse($id);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };

    }
}

?>
