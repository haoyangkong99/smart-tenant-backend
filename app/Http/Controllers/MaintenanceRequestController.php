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
        $requests = MaintenanceRequest::all();
        if ($requests->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
         }
         return $this->successfulQueryResponse($requests);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:property,id',
            'unit_id' => 'required|exists:property_units,id',
            'maintainer_id' => 'required|exists:maintainers,id',
            'issue_type' => 'required|string',
            'status' => 'required|in:PENDING,COMPLETED,INPROGRESS,CANCELLED',
            'issue_attachment' => 'nullable|file',
             // Ensure created_by is an integer
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('issue_attachment')) {
            $validatedData['issue_attachment'] = $request->file('issue_attachment')->store('attachments');
        }

        $maintenanceRequest = MaintenanceRequest::create($validatedData);
        return $this->successfulCreationResponse($maintenanceRequest) ;
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
            'property_id' => 'required|exists:property,id',
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
        return $this->successfulUpdateResponse($maintenanceRequest);
    }

    public function destroy($id)
    {
        try {
            $maintenanceRequest = MaintenanceRequest::findOrFail($id);
            $maintenanceRequest->delete();

            return $this->successfulDeleteResponse($id);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };

    }
}