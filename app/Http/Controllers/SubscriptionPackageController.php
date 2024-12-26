<?php
namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionPackageController extends Controller
{
    public function index()
    {
        $subscriptions = SubscriptionPackage::all();

        if ($subscriptions->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
        }

        return $this->successfulQueryResponse($subscriptions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:subscription_packages',
            'amount' => 'required|numeric|min:0',
            'interval' => 'required|integer',
            'staff_limit' => 'required|integer',
            'property_limit' => 'required|integer',
            'tenant_limit' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $subscription = SubscriptionPackage::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'interval' => $request->interval,
            'staff_limit' => $request->staff_limit,
            'property_limit' => $request->property_limit,
            'tenant_limit' => $request->tenant_limit,
            'created_by' => $this->getCurrentUserId(),
        ]);

        return $this->successfulCreationResponse($subscription) ;
    }

    public function show($id)
    {


        try {
            $subscription = SubscriptionPackage::find($id);
            return $this->successfulQueryResponse($subscription);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('SubscriptionPackage',$id);
        }
    }

    public function update(Request $request, $id)
    {
        $subscription = SubscriptionPackage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:subscription_packages,title,' . $id,
            'amount' => 'required|numeric|min:0',
            'interval' => 'required|integer',
            'staff_limit' => 'required|integer',
            'property_limit' => 'required|integer',
            'tenant_limit' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $subscription->update([
            'title' => $request->title,
            'amount' => $request->amount,
            'interval' => $request->interval,
            'staff_limit' => $request->staff_limit,
            'property_limit' => $request->property_limit,
            'tenant_limit' => $request->tenant_limit,
            'modified_by' => $this->getCurrentUserId(),
        ]);

        return $this->successfulUpdateResponse($subscription);
    }

    public function destroy($id)
    {

        try {
            $subscription = SubscriptionPackage::find($id);
            $subscription->delete();

            return $this->successfulDeleteResponse($id);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };
    }
}