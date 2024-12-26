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
            return $this->getQueryAllNotFoundResponse();
         }
         return $this->successfulQueryResponse($payments);
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
            return $this->validationErrorResponse($validator->errors());
        }

        $payment = Payment::create($validator->validated());
        return $this->successfulCreationResponse($payment) ;
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        try {
            $payment = Payment::with('invoice')->findOrFail($id);
            return $this->successfulQueryResponse($payment);
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
            return $this->validationErrorResponse($validator->errors());
        }

        $payment->update($validator->validated());
        return $this->successfulUpdateResponse($payment);
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->delete();

            return $this->successfulDeleteResponse($id);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };


    }
}