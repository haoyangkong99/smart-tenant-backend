<?php

namespace App\Http\Controllers;

use App\Models\Maintainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MaintainerController extends Controller
{
    public function index()
    {
        $maintainers = Maintainer::with(['user', 'property'])->get();
        if ($maintainers->isEmpty()) {
            $this->getQueryAllNotFoundResponse();
         }
        return response()->json($maintainers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:property,id',
            'maintenance_type' => 'required|string',
            'description' => 'nullable|string',
            'additional_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $maintainer = Maintainer::create([
            'user_id' => $request->user_id,
            'property_id' => $request->property_id,
            'maintenance_type' => $request->maintenance_type,
            'description' => $request->description,
            'additional_info' => $request->additional_info,
            'created_by' => $this->getCurrentUserId(),
        ]);
        return response()->json(['message' => 'Maintainer created successfully', 'maintainer' => $maintainer], 201);
    }

    public function show($id)
    {
        try {
            $maintainer = Maintainer::with(['user', 'property'])->findOrFail($id);
            return response()->json($maintainer);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('Maintainer',$id);
        }

    }

    public function update(Request $request, $id)
    {
        $maintainer = Maintainer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:property,id',
            'maintenance_type' => 'required|string',
            'description' => 'nullable|string',
            'additional_info' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $maintainer->update([
            'user_id' => $request->user_id,
            'property_id' => $request->property_id,
            'maintenance_type' => $request->maintenance_type,
            'description' => $request->description,
            'additional_info' => $request->additional_info,
            'modified_by' => $this->getCurrentUserId(),
        ]);
        return response()->json(['message' => 'Maintainer updated successfully', 'maintainer' => $maintainer]);
    }

    public function destroy($id)
    {
        try {
            $maintainer = Maintainer::findOrFail($id);
            $maintainer->delete();

            return response()->json(['message' => 'Maintainer deleted successfully']);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };

    }
}
?>