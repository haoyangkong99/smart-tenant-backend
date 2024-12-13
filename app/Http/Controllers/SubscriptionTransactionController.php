<?php
namespace App\Http\Controllers;

use App\Models\SubscriptionTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionTransactionController extends Controller
{
    public function index()
    {
        $transactions = SubscriptionTransaction::with(['user', 'subscriptionPackage'])->get();

        if ($transactions->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
        }

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:subscription_packages,id',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|string|max:255',
            'payment_status' => 'required|in:SUCCESS,FAILED,PENDING,CANCELLED',
            'receipt' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction = SubscriptionTransaction::create([
            'user_id' => $request->user_id,
            'package_id' => $request->package_id,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'payment_status' => $request->payment_status,
            'receipt' => $request->receipt,
            'created_by' => $this->getCurrentUserId(),
        ]);

        return response()->json(['message' => 'Transaction created successfully', 'transaction' => $transaction], 201);
    }

    public function show($id)
    {
        $transaction = SubscriptionTransaction::with(['user', 'subscriptionPackage'])->find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    public function update(Request $request, $id)
    {
        $transaction = SubscriptionTransaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'nullable|numeric|min:0',
            'payment_type' => 'nullable|string|max:255',
            'payment_status' => 'nullable|in:SUCCESS,FAILED,PENDING,CANCELLED',
            'receipt' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction->update([
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'payment_status' => $request->payment_status,
            'receipt' => $request->receipt,
            'modified_by' => $this->getCurrentUserId(),
        ]);

        return response()->json(['message' => 'Transaction updated successfully', 'transaction' => $transaction]);
    }

    public function destroy($id)
    {
        $transaction = SubscriptionTransaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}