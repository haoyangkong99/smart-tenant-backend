<?php
namespace App\Http\Controllers;

use App\Models\SubscriptionTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class SubscriptionTransactionController extends Controller
{
    // List all transactions
    public function index()
    {
        try {
            $transactions = SubscriptionTransaction::all();

            if ($transactions->isEmpty()) {
                return $this->getQueryAllNotFoundResponse();
            }

            return response()->json($transactions);
        } catch (Exception $e) {
            return $this->getGeneralExceptionResponse($e);
        }
    }

    // Store a new transaction
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'package_id' => 'required|exists:subscription_packages,id',
                'amount' => 'required|numeric|min:0',
                'payment_type' => 'required|string|max:255',
                'payment_status' => 'required|in:SUCCESS,FAILED,PENDING,CANCELLED,INVALID',
                'receipt' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
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
            if ($transaction->status=='INVALID')
            {
                return $this->getQueryInvalidResponse();
            }
            else
            {
                return $this->successfulCreationResponse($transaction);
            }

        } catch (Exception $e) {
            return $this->getGeneralExceptionResponse($e);
        }
    }

    // Show a specific transaction
    public function show($id)
    {
        try {
            $transaction = SubscriptionTransaction::where('id',$id)->firstOrFail();
            return $this->successfulQueryResponse($transaction);
        }
        catch (ModelNotFoundException $e){
            return $this->getQueryIDNotFoundResponse('SubscriptionTransaction',$id);
        }
        catch (Exception $e) {

            return $this->getGeneralExceptionResponse($e);
        }
    }

    // Update a transaction
    public function update(Request $request, $id)
    {
        try {
            $transaction = SubscriptionTransaction::find($id);

            if (!$transaction) {
                return $this->getQueryIDNotFoundResponse('Transaction',$id);
            }

            $validator = Validator::make($request->all(), [
                'amount' => 'nullable|numeric|min:0',
                'payment_type' => 'nullable|string|max:255',
                'payment_status' => 'nullable|in:SUCCESS,FAILED,PENDING,CANCELLED',
                'receipt' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $transaction->update([
                'amount' => $request->amount,
                'payment_type' => $request->payment_type,
                'payment_status' => $request->payment_status,
                'receipt' => $request->receipt,
                'modified_by' => $this->getCurrentUserId(),
            ]);

            return $this->successfulUpdateResponse($transaction);
        } catch (Exception $e) {
            return $this->getGeneralExceptionResponse($e);
        }
    }

    // Delete a transaction
    public function destroy($id)
    {
        try {
            $transaction = SubscriptionTransaction::find($id);

            if (!$transaction) {
                return $this->getQueryIDNotFoundResponse('SubscriptionTransaction',$id);
            }
            $transaction->delete();

            return $this->successfulDeleteResponse($id);
        } catch (Exception $e) {
            return $this->getGeneralExceptionResponse($e);
        }
    }
}
