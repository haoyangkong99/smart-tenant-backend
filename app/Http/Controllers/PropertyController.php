<?php
namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index()
    {
        $properties = Property::all();
        if ($properties->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
         }
         return $this->successfulQueryResponse($properties);
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'country' => 'required|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'post_code' => 'required|string',
            'image' => 'nullable|image',
             // Ensure created_by is an integer
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('properties', 'public');
        }

        $property = Property::create([
            'type' => $request->type,
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'post_code' => $request->post_code,
            'image' => $request->image,
            'created_by' => $this->getCurrentUserId(),
        ]);
        return $this->successfulCreationResponse($property) ;
    }

    /**
     * Display the specified property.
     */
    public function show($id)
    {
        try {
            $property = Property::findOrFail($id);
            return $this->successfulQueryResponse($property);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('Property',$id);
        }

    }

    /**
     * Update the specified property in storage.
     */
    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'post_code' => 'required|string',
            'image' => 'nullable|image',

        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('properties', 'public');
        }

        $property->update([
            'type' => $request->type,
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'post_code' => $request->post_code,
            'image' => $request->image,
            'modified_by' => $this->getCurrentUserId(),
        ]);
        return $this->successfulUpdateResponse($property);
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy($id)
    {
        try {
            $property = Property::findOrFail($id);
            $property->delete();

            return $this->successfulDeleteResponse($id);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };

    }
}