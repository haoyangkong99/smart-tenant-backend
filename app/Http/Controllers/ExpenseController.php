<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses.
     */
    public function index()
    {
        $expenses = Expense::with(['property', 'unit'])->get();
        if ($expenses->isEmpty()) {
            return $this->getQueryAllNotFoundResponse();
        }
        return $this->successfulQueryResponse($expenses);

    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:property_units,id',
            'receipt_number' => 'required|string|unique:expenses,receipt_number',
            'receipt_date' => 'required|date',
            'expense_type' => 'required|string',
            'total_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'attachment' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('attachment')) {
            $validatedData['attachment'] = $request->file('attachment')->store('attachments');
        }

        $expense = Expense::create(
            [
                'property_id' => $request->property_id,
                'unit_id'=>$request-> unit_id,
                'receipt_number'=>$request->receipt_number,
                'receipt_date'=>$request->receipt_date,
                'expense_type'=>$request->expense_type,
                'total_amount'=>$request->total_amount,
                'remarks'=>$request->remarks,
                'attachment'=>$request->attachment,
                'created_by' => $this->getCurrentUserId(),
            ]
        );
        return $this->successfulCreationResponse($expense) ;
    }

    /**
     * Display the specified expense.
     */
    public function show($id)
    {
        try {
            $expense = Expense::with(['property', 'unit'])->findOrFail($id);
            return response()->json($expense);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getQueryIDNotFoundResponse('Expense',$id);
        }
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:property_units,id',
            'receipt_number' => 'required|string|unique:expenses,receipt_number,' . $id,
            'receipt_date' => 'required|date',
            'expense_type' => 'required|string',
            'total_amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'attachment' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('attachment')) {
            $validatedData['attachment'] = $request->file('attachment')->store('attachments');
        }

        $expense->update([
            'property_id' => $request->property_id,
             'unit_id'=>$request-> unit_id,
             'receipt_number'=>$request->receipt_number,
             'receipt_date'=>$request->receipt_date,
             'expense_type'=>$request->expense_type,
             'total_amount'=>$request->total_amount,
             'remarks'=>$request->remarks,
             'attachment'=>$request->attachment,
             'modified_by' => $this->getCurrentUserId(),
        ]);
        return $this->successfulUpdateResponse($expense);
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();
            return $this->successfulDeleteResponse($id);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->getDeleteFailureResponse();
        }

    }


}
