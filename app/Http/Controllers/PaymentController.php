<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index()
    {
        $payments = Payment::with('invoice')->get();
        if ($payments->isEmpty()) {
            $this->getQueryAllNotFoundResponse();
         }
        return response()->json($payments);
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
            'invoice_number' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string',
            'status' => 'required|in:PENDING,COMPLETED,FAILED',
            'payment_method' => 'required|string',
             // Ensure created_by is an integer
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = Payment::create($validator->validated());
        return response()->json(['message' => 'Payment created successfully', 'payment' => $payment], 201);
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        try {
            $payment = Payment::with('invoice')->findOrFail($id);
            return response()->json($payment);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('Payment',$id);
        }

    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
            'invoice_number' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string',
            'status' => 'required|in:PENDING,COMPLETED,FAILED',
            'payment_method' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment->update($validator->validated());
        return response()->json(['message' => 'Payment updated successfully', 'payment' => $payment]);
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->delete();

            return response()->json(['message' => 'Payment deleted successfully']);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };


    }
}