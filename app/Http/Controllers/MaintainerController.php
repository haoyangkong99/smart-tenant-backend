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
            return $this->getQueryAllNotFoundResponse();
         }
         return $this->successfulQueryResponse($maintainers);
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
            return $this->validationErrorResponse($validator->errors());
        }

        $maintainer = Maintainer::create([
            'user_id' => $request->user_id,
            'property_id' => $request->property_id,
            'maintenance_type' => $request->maintenance_type,
            'description' => $request->description,
            'additional_info' => $request->additional_info,
            'created_by' => $this->getCurrentUserId(),
        ]);
        return $this->successfulCreationResponse($maintainer) ;
    }

    public function show($id)
    {
        try {
            $maintainer = Maintainer::with(['user', 'property'])->findOrFail($id);
            return $this->successfulQueryResponse($maintainer);
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
            return $this->validationErrorResponse($validator->errors());
        }

        $maintainer->update([
            'user_id' => $request->user_id,
            'property_id' => $request->property_id,
            'maintenance_type' => $request->maintenance_type,
            'description' => $request->description,
            'additional_info' => $request->additional_info,
            'modified_by' => $this->getCurrentUserId(),
        ]);
        return $this->successfulUpdateResponse($maintainer);
    }

    public function destroy($id)
    {
        try {
            $maintainer = Maintainer::findOrFail($id);
            $maintainer->delete();

            return $this->successfulDeleteResponse($id);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };

    }
}
?>