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

        return response()->json($subscriptions);
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
            return response()->json(['errors' => $validator->errors()], 422);
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

        return response()->json(['message' => 'Subscription package created successfully', 'subscription' => $subscription], 201);
    }

    public function show($id)
    {
        $subscription = SubscriptionPackage::find($id);

        if (!$subscription) {
            return $this->getQueryIDNotFoundResponse('SubscriptionPackage', $id);
        }

        return response()->json($subscription);
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
            return response()->json(['errors' => $validator->errors()], 422);
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

        return response()->json(['message' => 'Subscription package updated successfully', 'subscription' => $subscription]);
    }

    public function destroy($id)
    {
        $subscription = SubscriptionPackage::find($id);

        if (!$subscription) {
            return $this->getQueryIDNotFoundResponse('SubscriptionPackage', $id);
        }

        $subscription->delete();

        return response()->json(['message' => 'Subscription package deleted successfully']);
    }
}