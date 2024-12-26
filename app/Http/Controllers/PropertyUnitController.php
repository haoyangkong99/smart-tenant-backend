<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PropertyUnit;
use Illuminate\Support\Facades\Validator;
class PropertyUnitController extends Controller
{
    public function index()
    {
        $PropertyUnits = PropertyUnit::all();

        if ($PropertyUnits->isEmpty()) {
           return $this->getQueryAllNotFoundResponse();
        }

        return $this->successfulQueryResponse($PropertyUnits);
    }
   /**
    * Store a newly created PropertyUnit in storage.
    */
   public function store(Request $request)
   {

       $validator = Validator::make($request->all(), [
           'name' => 'required|string',
           'room_num' => 'required|integer',
           'property_id'=>'required|exists:property,id',

       ]);

       if ($validator->fails()) {
           return response()->json(['errors' => $validator->errors()], 422);
       }

       $PropertyUnit = PropertyUnit::create([
           'name' => $request->name,
           'room_num' =>  $request->room_num,
           'property_id'=> $request->property_id,
           'created_by' => $this->getCurrentUserId(),
       ]);

       return response()->json(['message' => 'PropertyUnit created successfully', 'PropertyUnit' => $PropertyUnit], 201);
   }

   /**
    * Display the specified PropertyUnit.
    */
   public function show($id)
   {
       try {
           $PropertyUnit = PropertyUnit::findOrFail($id);
           return response()->json($PropertyUnit);
       } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
           return $this->getQueryIDNotFoundResponse('PropertyUnit',$id);
       }

   }

   /**
    * Update the specified PropertyUnit in storage.
    */
   public function update(Request $request, $id)
   {
    try {
        $PropertyUnit = PropertyUnit::findOrFail($id);

        $validator = Validator::make($request->all(), [
         'name' => 'required|string',
         'room_num' => 'required|integer',
         'property_id'=>'required|exists:property,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $PropertyUnit->name=$request->name;
        $PropertyUnit->room_num=$request->room_num;
        $PropertyUnit->property_id= $request->property_id;
        $PropertyUnit->modified_by=$this->getCurrentUserId();
        $PropertyUnit->update($PropertyUnit->toArray());

        return response()->json(['message' => 'PropertyUnit updated successfully', 'PropertyUnit' => $PropertyUnit]);
    }
    catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
    {
        return $this->getQueryIDNotFoundResponse('PropertyUnit',$id);
    };


   }

   /**
    * Remove the specified PropertyUnit from storage.
    */
   public function destroy($id)
   {
       try {
           $PropertyUnit = PropertyUnit::findOrFail($id);
           $PropertyUnit->delete();

           return response()->json(['message' => 'PropertyUnit deleted successfully']);
       }
       catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
       {
           return $this->getDeleteFailureResponse();
       };

   }
}
