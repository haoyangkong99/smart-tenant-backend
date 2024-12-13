<?php
namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $requests = MaintenanceRequest::with(['property', 'unit', 'maintainer'])->get();
        if ($requests->isEmpty()) {
            $this->getQueryAllNotFoundResponse();
         }
        return response()->json($requests);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:property_units,id',
            'maintainer_id' => 'required|exists:maintainers,id',
            'issue_type' => 'required|string',
            'status' => 'required|in:PENDING,COMPLETED,INPROGRESS,CANCELLED',
            'issue_attachment' => 'nullable|file',
             // Ensure created_by is an integer
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('issue_attachment')) {
            $validatedData['issue_attachment'] = $request->file('issue_attachment')->store('attachments');
        }

        $maintenanceRequest = MaintenanceRequest::create($validatedData);
        return response()->json(['message' => 'Maintenance Request created successfully', 'maintenanceRequest' => $maintenanceRequest], 201);
    }

    public function show($id)
    {
        try {
            $request = MaintenanceRequest::with(['property', 'unit', 'maintainer'])->findOrFail($id);
            return response()->json($request);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('MaintenanceRequest',$id);
        }

    }

    public function update(Request $request, $id)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:property_units,id',
            'maintainer_id' => 'required|exists:maintainers,id',
            'issue_type' => 'required|string',
            'status' => 'required|in:PENDING,COMPLETED,INPROGRESS,CANCELLED',
            'issue_attachment' => 'nullable|file',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('issue_attachment')) {
            $validatedData['issue_attachment'] = $request->file('issue_attachment')->store('attachments');
        }

        $maintenanceRequest->update($validatedData);
        return response()->json(['message' => 'Maintenance Request updated successfully', 'maintenanceRequest' => $maintenanceRequest]);
    }

    public function destroy($id)
    {
        try {
            $maintenanceRequest = MaintenanceRequest::findOrFail($id);
            $maintenanceRequest->delete();

            return response()->json(['message' => 'Maintenance Request deleted successfully']);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };

    }
}