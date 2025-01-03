<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $settings = GeneralSetting::all();
        if ($settings->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
         }
         return $this->successfulQueryResponse($settings);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'logo' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust validation rules as needed
            'landing_page_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust validation rules as needed
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validatedData = $validator->validated();

        $validatedData['logo'] = $request->file('logo')->store('logos');

        if ($request->hasFile('landing_page_logo')) {
            $validatedData['landing_page_logo'] = $request->file('landing_page_logo')->store('logos');
        }

        $setting = GeneralSetting::create([
            'name' =>  $request->name,
            'logo' =>  $request->logo,
            'landing_page_logo' =>  $request->landing_page_logo,
        ]);
        return $this->successfulCreationResponse($setting) ;
    }

    public function update(Request $request, $id)
    {
        $setting = GeneralSetting::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust validation rules as needed
            'landing_page_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust validation rules as needed
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('logo')) {
            $validatedData['logo'] = $request->file('logo')->store('logos');
        }

        if ($request->hasFile('landing_page_logo')) {
            $validatedData['landing_page_logo'] = $request->file('landing_page_logo')->store('logos');
        }

        $setting->update($validatedData);
        return $this->successfulUpdateResponse($setting);
    }
}