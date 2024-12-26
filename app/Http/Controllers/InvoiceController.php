<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['property', 'unit', 'items'])->get();
        if ($invoices->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
         }
         return $this->successfulQueryResponse($invoices);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:property_units,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'invoice_month' => 'required|string',
            'invoice_end_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
             // Ensure created_by is an integer
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $invoice = Invoice::create($validator->validated());
        return $this->successfulCreationResponse($invoice) ;
    }

    public function show($id)
    {
        try {
            $invoice = Invoice::with(['property', 'unit', 'items'])->findOrFail($id);
            return response()->json($invoice);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('Invoice',$id);
        }


    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:property_units,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $id,
            'invoice_month' => 'required|string',
            'invoice_end_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'remarks' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $invoice->update($validator->validated());
        return $this->successfulUpdateResponse($invoice);
    }

    public function destroy($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->delete();

            return $this->successfulDeleteResponse($id);
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->getDeleteFailureResponse();
        };


    }
}

?>