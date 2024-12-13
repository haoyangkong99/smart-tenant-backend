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
            $this->getQueryAllNotFoundResponse();
         }
        return response()->json($leases);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('contract')) {
            $validatedData['contract'] = $request->file('contract')->store('contracts');
        }

        $lease = Lease::create($validatedData);
        return response()->json(['message' => 'Lease created successfully', 'lease' => $lease], 201);
    }

    public function show($id)
    {
        try {
            $lease = Lease::with(['property', 'unit', 'tenant'])->findOrFail($id);
            return response()->json($lease);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('Lease',$id);
        }

    }

    public function update(Request $request, $id)
    {
        $lease = Lease::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('contract')) {
            $validatedData['contract'] = $request->file('contract')->store('contracts');
        }

        $lease->update($validatedData);
        return response()->json(['message' => 'Lease updated successfully', 'lease' => $lease]);
    }

    public function destroy($id)
    {
        try {
            $lease = Lease::findOrFail($id);
            $lease->delete();

            return response()->json(['message' => 'Lease deleted successfully']);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };

    }
}

?>
